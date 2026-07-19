<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('basts', function (Blueprint $table) {
            $table->id();
            $table->string('no_bast')->unique();
            $table->unsignedBigInteger('id_kanban_task')->nullable()->index();
            $table->unsignedBigInteger('id_quotation')->nullable()->index();
            $table->enum('entity', ['Reftech', 'Kojisha'])->default('Reftech');
            $table->string('customer_name');
            $table->string('work_title');
            $table->string('po_number')->nullable();
            $table->date('work_date');
            $table->text('test_running_result')->nullable();
            $table->unsignedBigInteger('created_by')->index();
            $table->timestamps();

            $table->foreign('id_kanban_task')->references('id')->on('kanban_tasks')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('basts');
    }
};
