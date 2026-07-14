<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kanban_task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('kanban_tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('kanban_task_comment_mentions', function (Blueprint $table) {
            $table->foreignId('comment_id')->constrained('kanban_task_comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->primary(['comment_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('kanban_task_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('kanban_tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('activity_type');
            $table->json('activity_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kanban_task_activities');
        Schema::dropIfExists('kanban_task_comment_mentions');
        Schema::dropIfExists('kanban_task_comments');
    }
};
