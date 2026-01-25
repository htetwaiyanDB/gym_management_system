<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trainer_bookings', function (Blueprint $table) {
            $table->unsignedInteger('sessions_remaining')->default(0)->after('sessions_count');
        });

        DB::table('trainer_bookings')
            ->where('sessions_remaining', 0)
            ->update(['sessions_remaining' => DB::raw('sessions_count')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_bookings', function (Blueprint $table) {
            $table->dropColumn('sessions_remaining');
        });
    }
};
