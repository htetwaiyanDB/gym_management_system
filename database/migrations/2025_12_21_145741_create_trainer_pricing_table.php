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
       Schema::create('trainer_pricing', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trainer_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->decimal('price_per_session', 10, 2);

            $table->timestamp('updated_at')->useCurrent();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_pricing');
    }
};
