<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tool_assignment_technicians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Backfill: preserve existing behavior for users who already had tools
        // assigned or hold the Technician role at migration time. After this,
        // list membership is managed manually by Admin via the Add/Remove UI.
        $userIds = \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'Technician')
            ->pluck('id')
            ->merge(
                \Illuminate\Support\Facades\DB::table('fixed_asset')
                    ->where('type', 'Tools')
                    ->whereNotNull('id_pic')
                    ->pluck('id_pic')
            )
            ->unique();

        $now = now();
        $rows = $userIds->map(fn ($id) => [
            'user_id' => $id,
            'created_at' => $now,
            'updated_at' => $now,
        ])->values()->all();

        if (!empty($rows)) {
            \Illuminate\Support\Facades\DB::table('tool_assignment_technicians')->insert($rows);
        }
    }

    public function down()
    {
        Schema::dropIfExists('tool_assignment_technicians');
    }
};
