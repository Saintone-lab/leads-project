<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kanban_tasks', function (Blueprint $table) {
            $table->json('labels')->nullable()->after('due_date');
        });

        Schema::create('kanban_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('kanban_tasks')->onDelete('cascade');
            $table->string('title')->default('Checklist');
            $table->timestamps();
        });

        Schema::create('kanban_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('kanban_checklists')->onDelete('cascade');
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kanban_checklist_items');
        Schema::dropIfExists('kanban_checklists');
        
        Schema::table('kanban_tasks', function (Blueprint $table) {
            $table->dropColumn('labels');
        });
    }
};
