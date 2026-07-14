<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\KanbanTaskComment;
use App\Models\KanbanTaskActivity;
use App\Models\KanbanChecklist;
use App\Models\KanbanChecklistItem;
use App\Models\KanbanTaskAttachment;
use App\Models\KanbanTaskDeleteRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'Admin') {
            $boards = KanbanBoard::with('creator')->orderBy('title')->get();
        } else {
            $boards = $user->kanbanBoards()->with('creator')->orderBy('title')->get();
        }

        // Fetch all active users for board creation members select
        $users = User::where('active', '1')->orderBy('name')->get();

        return view('pages.kanban.index', compact('boards', 'users'));
    }

    public function storeBoard(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang bisa membuat papan Kanban.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'columns' => 'required|array|min:1',
            'columns.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $board = KanbanBoard::create([
                'title' => $request->title,
                'description' => $request->description,
                'created_by' => Auth::id(),
            ]);

            // Sync members
            if ($request->has('member_ids')) {
                $board->members()->sync($request->member_ids);
            }

            // Create columns
            foreach ($request->columns as $index => $colTitle) {
                KanbanColumn::create([
                    'board_id' => $board->id,
                    'title' => $colTitle,
                    'position' => $index,
                ]);
            }
        });

        return redirect()->route('kanban.index')->with('success', 'Papan Kanban berhasil dibuat!');
    }

    public function showBoard($id)
    {
        $user = Auth::user();
        $board = KanbanBoard::with(['columns', 'members'])->findOrFail($id);

        // Check permission: Admin can see all, members can see their boards
        if ($user->role !== 'Admin' && !$board->members->contains($user->id)) {
            abort(403, 'Anda tidak memiliki akses ke papan Kanban ini.');
        }

        // Fetch all active users for settings member select
        $users = User::where('active', '1')->orderBy('name')->get();

        // Fetch user boards for board switcher dropdown
        if ($user->role === 'Admin') {
            $myBoards = KanbanBoard::orderBy('title')->get();
        } else {
            $myBoards = $user->kanbanBoards()->orderBy('title')->get();
        }

        return view('pages.kanban.board', compact('board', 'users', 'myBoards'));
    }

    public function updateBoard(Request $request, $id)
    {
        if (Auth::user()->role !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board = KanbanBoard::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'columns' => 'required|array|min:1',
            'columns.*.id' => 'nullable|integer',
            'columns.*.title' => 'required|string|max:255',
            'labels' => 'nullable|array',
            'labels.*' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $board) {
            $board->update([
                'title' => $request->title,
                'description' => $request->description,
                'labels' => $request->labels,
            ]);

            // Sync members
            $board->members()->sync($request->member_ids ?? []);

            // Handle Columns update/create/delete
            $submittedColumnIds = collect($request->columns)->pluck('id')->filter()->toArray();

            // 1. Delete columns not in the request
            $board->columns()->whereNotIn('id', $submittedColumnIds)->delete();

            // 2. Create or Update columns
            foreach ($request->columns as $index => $colData) {
                if (isset($colData['id']) && $colData['id']) {
                    KanbanColumn::where('id', $colData['id'])->update([
                        'title' => $colData['title'],
                        'position' => $index,
                    ]);
                } else {
                    KanbanColumn::create([
                        'board_id' => $board->id,
                        'title' => $colData['title'],
                        'position' => $index,
                    ]);
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Papan Kanban berhasil diperbarui!']);
    }

    public function destroyBoard($id)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang bisa menghapus papan Kanban.');
        }

        $board = KanbanBoard::findOrFail($id);
        $board->delete();

        return redirect()->route('kanban.index')->with('success', 'Papan Kanban berhasil dihapus!');
    }

    public function getBoardData($id)
    {
        $user = Auth::user();
        $board = KanbanBoard::with(['columns.tasks.assignees', 'columns.tasks.checklists.items'])->findOrFail($id);

        if ($user->role !== 'Admin' && !$board->members->contains($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = [];
        foreach ($board->columns as $column) {
            $items = [];
            foreach ($column->tasks as $task) {
                $taskAssignees = $task->assignees->map(function ($u) {
                    return [
                        'name' => $u->name,
                        'avatar' => $u->image ? '/' . $u->image : null,
                    ];
                });

                $totalChecklistItems = 0;
                $completedChecklistItems = 0;
                foreach ($task->checklists as $checklist) {
                    $totalChecklistItems += $checklist->items->count();
                    $completedChecklistItems += $checklist->items->where('is_completed', true)->count();
                }

                $items[] = [
                    'id' => (string) $task->id,
                    'title' => $task->title,
                    'description' => $task->description ?? '',
                    'due_date' => $task->due_date ? $task->due_date : '',
                    'assignees' => $taskAssignees,
                    'labels' => $task->labels ?? [],
                    'total_checklists' => $totalChecklistItems,
                    'completed_checklists' => $completedChecklistItems,
                    'priority' => $task->priority ?? 'medium',
                ];
            }
            $data[] = [
                'id' => 'column_' . $column->id,
                'title' => $column->title,
                'item' => $items
            ];
        }

        return response()->json($data);
    }

    public function moveTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:kanban_tasks,id',
            'target_column_id' => 'required|string',
            'new_position' => 'required|integer',
        ]);

        $columnId = (int) str_replace('column_', '', $request->target_column_id);
        $taskId = $request->task_id;
        $newPos = $request->new_position;

        $task = KanbanTask::findOrFail($taskId);
        $oldColumnId = $task->column_id;
        $oldPos = $task->position;
        $oldCol = KanbanColumn::find($oldColumnId);
        $newCol = KanbanColumn::find($columnId);

        DB::transaction(function () use ($task, $oldColumnId, $oldPos, $columnId, $newPos) {
            if ($oldColumnId == $columnId) {
                // Moving inside the same column
                if ($oldPos < $newPos) {
                    KanbanTask::where('column_id', $columnId)
                        ->where('position', '>', $oldPos)
                        ->where('position', '<=', $newPos)
                        ->decrement('position');
                } else if ($oldPos > $newPos) {
                    KanbanTask::where('column_id', $columnId)
                        ->where('position', '>=', $newPos)
                        ->where('position', '<', $oldPos)
                        ->increment('position');
                }
            } else {
                // Moving to a different column
                // 1. Decrement positions in source column for tasks after the old position
                KanbanTask::where('column_id', $oldColumnId)
                    ->where('position', '>', $oldPos)
                    ->decrement('position');

                // 2. Increment positions in target column for tasks at or after the new position
                KanbanTask::where('column_id', $columnId)
                    ->where('position', '>=', $newPos)
                    ->increment('position');
            }

            // 3. Update task column and position
            $task->update([
                'column_id' => $columnId,
                'position' => $newPos,
            ]);
        });

        if ($oldColumnId != $columnId) {
            $this->logActivity($task->id, 'move', [
                'from' => $oldCol ? $oldCol->title : 'Unknown',
                'to' => $newCol ? $newCol->title : 'Unknown'
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'board_id' => 'required|exists:kanban_boards,id',
            'column_id' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string|in:high,medium,low',
        ]);

        $columnId = (int) str_replace('column_', '', $request->column_id);

        // Get max position in this column
        $maxPos = KanbanTask::where('column_id', $columnId)->max('position');
        $position = is_null($maxPos) ? 0 : $maxPos + 1;

        $task = KanbanTask::create([
            'board_id' => $request->board_id,
            'column_id' => $columnId,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'position' => $position,
            'priority' => $request->priority ?? 'medium',
        ]);

        // Log creation activity
        $this->logActivity($task->id, 'create');

        if ($request->assigned_to) {
            $task->assignees()->sync([$request->assigned_to]);
        }

        $taskLoad = KanbanTask::with('assignees')->find($task->id);

        $taskAssignees = $taskLoad->assignees->map(function ($u) {
            return [
                'name' => $u->name,
                'avatar' => $u->image ? '/' . $u->image : null,
            ];
        });

        return response()->json([
            'success' => true,
            'task' => [
                'id' => (string) $taskLoad->id,
                'title' => $taskLoad->title,
                'description' => $taskLoad->description ?? '',
                'due_date' => $taskLoad->due_date ? $taskLoad->due_date : '',
                'assignees' => $taskAssignees,
                'priority' => $taskLoad->priority ?? 'medium',
            ]
        ]);
    }

    public function updateTask(Request $request, $id)
    {
        $task = KanbanTask::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
            'due_date' => 'nullable|date',
            'column_id' => 'nullable|string',
            'priority' => 'nullable|string|in:high,medium,low',
        ]);

        DB::transaction(function () use ($request, $task) {
            $oldTitle = $task->title;
            $oldDesc = $task->description;
            $oldDueDate = $task->due_date;
            $oldColumnId = $task->column_id;
            $oldPriority = $task->priority ?? 'medium';
            
            $columnId = $request->column_id ? (int) str_replace('column_', '', $request->column_id) : $oldColumnId;

            // If column has changed
            if ($columnId != $oldColumnId) {
                // 1. Decrement positions in source column for tasks after the old position
                KanbanTask::where('column_id', $oldColumnId)
                    ->where('position', '>', $task->position)
                    ->decrement('position');

                // 2. Get max position in new column
                $maxPos = KanbanTask::where('column_id', $columnId)->max('position');
                $newPos = is_null($maxPos) ? 0 : $maxPos + 1;

                $task->update([
                    'column_id' => $columnId,
                    'position' => $newPos,
                ]);

                $oldCol = KanbanColumn::find($oldColumnId);
                $newCol = KanbanColumn::find($columnId);
                $this->logActivity($task->id, 'move', [
                    'from' => $oldCol ? $oldCol->title : 'Unknown',
                    'to' => $newCol ? $newCol->title : 'Unknown'
                ]);
            }

            $newAssigneesIds = $request->assignees ?? [];
            $oldAssigneesIds = $task->assignees()->pluck('users.id')->toArray();

            sort($oldAssigneesIds);
            sort($newAssigneesIds);

            if ($oldAssigneesIds !== $newAssigneesIds) {
                $task->assignees()->sync($newAssigneesIds);
                
                $oldNames = User::whereIn('id', $oldAssigneesIds)->pluck('name')->toArray();
                $newNames = User::whereIn('id', $newAssigneesIds)->pluck('name')->toArray();
                
                $this->logActivity($task->id, 'update_assignee', [
                    'old' => implode(', ', $oldNames) ?: 'tidak ditugaskan',
                    'new' => implode(', ', $newNames) ?: 'tidak ditugaskan'
                ]);
            }

            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'assigned_to' => !empty($newAssigneesIds) ? $newAssigneesIds[0] : null,
                'due_date' => $request->due_date,
                'priority' => $request->priority ?? $task->priority,
            ]);

            // Log other changes
            if ($oldTitle !== $request->title) {
                $this->logActivity($task->id, 'update_title', ['old' => $oldTitle, 'new' => $request->title]);
            }
            if ($oldDesc !== $request->description) {
                $this->logActivity($task->id, 'update_description');
            }
            if ($oldDueDate != $request->due_date) {
                $this->logActivity($task->id, 'update_due_date', ['old' => $oldDueDate, 'new' => $request->due_date]);
            }
            if ($oldPriority !== $request->priority) {
                $priorityNames = ['high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'];
                $oldName = $priorityNames[$oldPriority] ?? ($oldPriority ?? 'Sedang');
                $newName = $priorityNames[$request->priority] ?? ($request->priority ?? 'Sedang');
                $this->logActivity($task->id, 'update_priority', ['old' => $oldName, 'new' => $newName]);
            }
        });

        $taskLoad = KanbanTask::with(['assignees', 'column'])->find($task->id);

        return response()->json([
            'success' => true,
            'task' => [
                'id' => (string) $taskLoad->id,
                'title' => $taskLoad->title,
                'description' => $taskLoad->description ?? '',
                'due_date' => $taskLoad->due_date ? $taskLoad->due_date : '',
                'assignees' => $taskLoad->assignees->pluck('id')->toArray(),
                'column_id' => $taskLoad->column_id,
                'column_title' => $taskLoad->column->title,
                'priority' => $taskLoad->priority ?? 'medium',
            ]
        ]);
    }

    public function destroyTask($id)
    {
        $task = KanbanTask::findOrFail($id);
        
        DB::transaction(function () use ($task) {
            // Decrement positions of tasks after this task in the same column
            KanbanTask::where('column_id', $task->column_id)
                ->where('position', '>', $task->position)
                ->decrement('position');

            $task->delete();
        });

        return response()->json(['success' => true]);
    }

    public function getTaskDetails($id)
    {
        $task = KanbanTask::with([
            'column',
            'assignees',
            'comments.user',
            'activities.user',
            'checklists.items',
            'attachments.user'
        ])->findOrFail($id);

        // Format comments
        $comments = $task->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'type' => 'comment',
                'actor' => $comment->user->name,
                'actor_avatar' => $comment->user->image ? '/' . $comment->user->image : null,
                'actor_initials' => strtoupper(substr($comment->user->name, 0, 1)),
                'text' => $comment->comment,
                'created_at_raw' => $comment->created_at,
                'created_at' => $comment->created_at->format('M d, Y, g:i A'),
                'created_at_human' => $comment->created_at->diffForHumans(),
                'user_id' => $comment->user_id,
            ];
        });

        // Format activities
        $activities = $task->activities->map(function ($activity) {
            $actor = $activity->user ? $activity->user->name : 'System';
            $text = '';
            $data = $activity->activity_data;

            switch ($activity->activity_type) {
                case 'create':
                    $text = 'membuat tugas ini';
                    break;
                case 'move':
                    $from = isset($data['from']) ? $data['from'] : 'Unknown';
                    $to = isset($data['to']) ? $data['to'] : 'Unknown';
                    $text = "memindahkan tugas dari kolom \"{$from}\" ke \"{$to}\"";
                    break;
                case 'update_title':
                    $old = isset($data['old']) ? $data['old'] : '';
                    $new = isset($data['new']) ? $data['new'] : '';
                    $text = "mengubah judul tugas dari \"{$old}\" menjadi \"{$new}\"";
                    break;
                case 'update_description':
                    $text = 'memperbarui deskripsi tugas';
                    break;
                case 'update_assignee':
                    $old = isset($data['old']) && $data['old'] ? $data['old'] : 'belum ditugaskan';
                    $new = isset($data['new']) && $data['new'] ? $data['new'] : 'tidak ditugaskan';
                    $text = "mengubah penugasan dari \"{$old}\" ke \"{$new}\"";
                    break;
                case 'update_due_date':
                    $old = isset($data['old']) && $data['old'] ? $data['old'] : 'tanpa batas waktu';
                    $new = isset($data['new']) && $data['new'] ? $data['new'] : 'dihapus';
                    $text = "mengubah tanggal jatuh tempo dari \"{$old}\" ke \"{$new}\"";
                    break;
                case 'upload_attachment':
                    $fileName = isset($data['file_name']) ? $data['file_name'] : 'file';
                    $text = "mengunggah lampiran \"{$fileName}\"";
                    break;
                case 'delete_attachment':
                    $fileName = isset($data['file_name']) ? $data['file_name'] : 'file';
                    $text = "menghapus lampiran \"{$fileName}\"";
                    break;
                case 'update_priority':
                    $old = isset($data['old']) ? $data['old'] : 'Sedang';
                    $new = isset($data['new']) ? $data['new'] : 'Sedang';
                    $text = "mengubah prioritas dari \"{$old}\" menjadi \"{$new}\"";
                    break;
                case 'request_delete':
                    $text = 'mengajukan penghapusan tugas ini kepada Admin';
                    break;
                default:
                    $text = 'memperbarui tugas ini';
                    break;
            }

            return [
                'id' => $activity->id,
                'type' => 'activity',
                'actor' => $actor,
                'actor_avatar' => $activity->user && $activity->user->image ? '/' . $activity->user->image : null,
                'actor_initials' => $activity->user ? strtoupper(substr($activity->user->name, 0, 1)) : 'S',
                'text' => $text,
                'created_at_raw' => $activity->created_at,
                'created_at' => $activity->created_at->format('M d, Y, g:i A'),
                'created_at_human' => $activity->created_at->diffForHumans(),
            ];
        });

        // Combine chronologically
        $feed = $comments->concat($activities)->sortByDesc('created_at_raw')->values()->map(function ($item) {
            unset($item['created_at_raw']);
            return $item;
        });

        // Format checklists
        $checklists = $task->checklists->map(function ($checklist) {
            $total = $checklist->items->count();
            $completed = $checklist->items->where('is_completed', true)->count();
            $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

            return [
                'id' => $checklist->id,
                'title' => $checklist->title,
                'percent' => $percent,
                'items' => $checklist->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'is_completed' => (bool)$item->is_completed,
                    ];
                }),
            ];
        });

        // Format attachments
        $attachments = $task->attachments->map(function ($att) {
            return [
                'id' => $att->id,
                'file_name' => $att->file_name,
                'file_path' => '/' . $att->file_path,
                'file_size' => $att->file_size ? round($att->file_size / 1024, 1) . ' KB' : 'Unknown size',
                'file_type' => $att->file_type,
                'uploaded_by' => $att->user ? $att->user->name : 'Unknown',
                'uploaded_at' => $att->created_at->format('M d, Y, g:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description ?? '',
                'due_date' => $task->due_date,
                'assignees' => $task->assignees->pluck('id')->toArray(),
                'column_id' => $task->column_id,
                'column_title' => $task->column->title,
                'labels' => $task->labels ?? [],
                'priority' => $task->priority ?? 'medium',
            ],
            'feed' => $feed,
            'checklists' => $checklists,
            'attachments' => $attachments,
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $task = KanbanTask::findOrFail($id);

        $comment = KanbanTaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // Parse mentions
        $activeUsers = User::where('active', '1')->get();
        foreach ($activeUsers as $user) {
            if (stripos($request->comment, '@' . $user->name) !== false) {
                $comment->mentions()->attach($user->id);
            }
        }

        return response()->json(['success' => true]);
    }

    public function updateComment(Request $request, $id)
    {
        $comment = KanbanTaskComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyComment($id)
    {
        $comment = KanbanTaskComment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    public function updateLabels(Request $request, $id)
    {
        $task = KanbanTask::findOrFail($id);

        $request->validate([
            'labels' => 'nullable|array',
        ]);

        $task->update([
            'labels' => $request->labels,
        ]);

        return response()->json(['success' => true]);
    }

    public function storeChecklist(Request $request, $taskId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $checklist = KanbanChecklist::create([
            'task_id' => $taskId,
            'title' => $request->title,
        ]);

        return response()->json(['success' => true, 'checklist' => $checklist]);
    }

    public function destroyChecklist($id)
    {
        $checklist = KanbanChecklist::findOrFail($id);
        $checklist->delete();

        return response()->json(['success' => true]);
    }

    public function storeChecklistItem(Request $request, $checklistId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $item = KanbanChecklistItem::create([
            'checklist_id' => $checklistId,
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function toggleChecklistItem(Request $request, $id)
    {
        $item = KanbanChecklistItem::findOrFail($id);
        $item->update([
            'is_completed' => !$item->is_completed,
        ]);

        return response()->json(['success' => true, 'is_completed' => $item->is_completed]);
    }

    public function destroyChecklistItem($id)
    {
        $item = KanbanChecklistItem::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $task = KanbanTask::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            // Store file in public/uploads/kanban
            $file->move(public_path('uploads/kanban'), $filename);
            $filePath = 'uploads/kanban/' . $filename;

            $attachment = KanbanTaskAttachment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientOriginalExtension(),
            ]);

            // Log activity
            $this->logActivity($task->id, 'upload_attachment', ['file_name' => $attachment->file_name]);

            return response()->json(['success' => true, 'attachment' => $attachment]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function destroyAttachment($id)
    {
        $attachment = KanbanTaskAttachment::findOrFail($id);

        // Delete from local disk
        $fullPath = public_path($attachment->file_path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        // Log activity
        $this->logActivity($attachment->task_id, 'delete_attachment', ['file_name' => $attachment->file_name]);

        $attachment->delete();

        return response()->json(['success' => true]);
    }

    public function requestDeleteTask(Request $request, $id)
    {
        $task = KanbanTask::findOrFail($id);
        $user = Auth::user();

        // Check if there is already a pending delete request for this task
        $exists = KanbanTaskDeleteRequest::where('task_id', $task->id)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            KanbanTaskDeleteRequest::create([
                'board_id' => $task->board_id,
                'task_id' => $task->id,
                'requested_by' => $user->id,
                'status' => 'pending',
            ]);

            // Optional: log request activity
            $this->logActivity($task->id, 'request_delete', ['requested_by' => $user->name]);
        }

        return response()->json(['success' => true]);
    }

    public function getDeleteRequests($boardId)
    {
        $board = KanbanBoard::findOrFail($boardId);
        $user = Auth::user();

        // Only board creator/owner or admin user can view delete requests
        if ($user->role !== 'Admin' && $board->created_by !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $requests = KanbanTaskDeleteRequest::with(['task', 'requester'])
            ->where('board_id', $boardId)
            ->where('status', 'pending')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'task_id' => $req->task_id,
                    'task_title' => $req->task ? $req->task->title : 'Unknown Task',
                    'requested_by_name' => $req->requester ? $req->requester->name : 'Unknown',
                    'requested_by_avatar' => $req->requester && $req->requester->image ? '/' . $req->requester->image : null,
                    'created_at' => $req->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'requests' => $requests,
        ]);
    }

    public function approveDeleteRequest($id)
    {
        $req = KanbanTaskDeleteRequest::with('board')->findOrFail($id);
        $user = Auth::user();

        // Only board creator/owner or admin user can approve delete requests
        if ($user->role !== 'Admin' && $req->board->created_by !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::transaction(function () use ($req) {
            $task = KanbanTask::find($req->task_id);
            if ($task) {
                // Decrement positions of tasks after this task in the same column
                KanbanTask::where('column_id', $task->column_id)
                    ->where('position', '>', $task->position)
                    ->decrement('position');

                $task->delete();
            }

            // Remove all requests for this task
            KanbanTaskDeleteRequest::where('task_id', $req->task_id)->delete();
        });

        return response()->json(['success' => true]);
    }

    public function rejectDeleteRequest($id)
    {
        $req = KanbanTaskDeleteRequest::with('board')->findOrFail($id);
        $user = Auth::user();

        // Only board creator/owner or admin user can reject delete requests
        if ($user->role !== 'Admin' && $req->board->created_by !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Just delete/dismiss the request record
        $req->delete();

        return response()->json(['success' => true]);
    }

    private function logActivity($taskId, $type, $data = null)
    {
        KanbanTaskActivity::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'activity_type' => $type,
            'activity_data' => $data,
        ]);
    }
}
