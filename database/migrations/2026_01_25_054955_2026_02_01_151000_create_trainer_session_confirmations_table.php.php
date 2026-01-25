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
        Schema::create('trainer_session_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_booking_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64);
            $table->timestamp('user_confirmed_at')->nullable();
            $table->timestamp('trainer_confirmed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['trainer_booking_id', 'token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_session_confirmations');
    }
};
