<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trainer_bookings', function (Blueprint $table) {
            $table->dateTime('sessions_start_date')->nullable()->after('sessions_remaining');
            $table->dateTime('sessions_end_date')->nullable()->after('sessions_start_date');
            $table->dateTime('month_start_date')->nullable()->after('sessions_end_date');
            $table->dateTime('month_end_date')->nullable()->after('month_start_date');
            $table->dateTime('hold_start_date')->nullable()->after('month_end_date');
            $table->dateTime('hold_end_date')->nullable()->after('hold_start_date');
            $table->unsignedInteger('total_hold_days')->default(0)->after('hold_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'sessions_start_date',
                'sessions_end_date',
                'month_start_date',
                'month_end_date',
                'hold_start_date',
                'hold_end_date',
                'total_hold_days',
            ]);
        });
    }
};
