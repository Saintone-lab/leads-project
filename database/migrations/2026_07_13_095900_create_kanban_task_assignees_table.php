<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kanban_task_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('kanban_tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing single assignees to the pivot table
        DB::statement("
            INSERT INTO kanban_task_assignees (task_id, user_id, created_at, updated_at)
            SELECT id, assigned_to, NOW(), NOW()
            FROM kanban_tasks
            WHERE assigned_to IS NOT NULL
        ");
    }

    public function down()
    {
        Schema::dropIfExists('kanban_task_assignees');
    }
};
