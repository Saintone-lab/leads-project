<?php

namespace App\Http\Controllers;

use App\Models\ToolAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolAuditVerificationController extends Controller
{
    protected function guardAdmin()
    {
        if (Auth::user()->role != 'Admin') {
            abort(403, 'Hanya Admin yang bisa mengakses Verifikasi Audit Tools.');
        }
    }

    public function index(Request $request)
    {
        $this->guardAdmin();

        $status = $request->get('status', 'Submitted');

        $audits = ToolAudit::with(['technician', 'period'])
            ->where('status_submit', $status)
            ->orderByDesc('submitted_at')
            ->get();

        return view('pages.admin.tool-audit-verification.index', compact('audits', 'status'));
    }

    public function show($id)
    {
        $this->guardAdmin();

        $audit = ToolAudit::with(['technician', 'period', 'items.fixedAsset.toolsMaster'])
            ->findOrFail($id);

        return view('pages.admin.tool-audit-verification.show', compact('audit'));
    }

    public function decide(Request $request, $id)
    {
        $this->guardAdmin();

        $audit = ToolAudit::with('items')->findOrFail($id);

        if ($audit->status_submit != 'Submitted') {
            abort(403, 'Audit ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'action' => 'required|in:verify,reject',
            'catatan_admin' => $request->action == 'reject' ? 'required|string' : 'nullable|string',
        ], [
            'catatan_admin.required' => 'Catatan wajib diisi kalau audit ditolak / minta perbaikan.',
        ]);

        foreach ($audit->items as $item) {
            $itemStatus = $request->input("item_status.{$item->id}");
            $itemNote = $request->input("item_note.{$item->id}");
            if ($itemStatus) {
                $item->status_verifikasi_item = $itemStatus;
                $item->catatan_admin_item = $itemNote;
                $item->save();
            }
        }

        $audit->catatan_admin = $request->catatan_admin;
        $audit->verified_by = Auth::id();
        $audit->verified_at = Carbon::now();
        $audit->status_submit = $request->action == 'verify' ? 'Verified' : 'Rejected';
        $audit->save();

        $message = $request->action == 'verify'
            ? 'Audit ' . $audit->no_audit . ' sudah diverifikasi.'
            : 'Audit ' . $audit->no_audit . ' dikembalikan ke teknisi untuk diperbaiki.';

        return redirect()->route('tool-audit-verification.index')->with('success', $message);
    }
}
