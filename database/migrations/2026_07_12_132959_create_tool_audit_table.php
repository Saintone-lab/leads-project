<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tool_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_audit_period');
            $table->unsignedBigInteger('id_technician');
            $table->string('no_audit')->nullable();
            $table->enum('status_submit', ['Draft', 'Submitted', 'Verified', 'Rejected'])->default('Draft');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('catatan_admin')->nullable();
            $table->integer('total_tools')->default(0);
            $table->integer('total_ada')->default(0);
            $table->integer('total_rusak')->default(0);
            $table->integer('total_hilang')->default(0);
            $table->timestamps();
            $table->unique(['id_audit_period', 'id_technician']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tool_audit');
    }
};
