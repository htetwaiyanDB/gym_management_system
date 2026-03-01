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
        Schema::table('boxing_bookings', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_price');
            $table->decimal('final_price', 10, 2)->default(0)->after('discount_amount');
        });

        DB::table('boxing_bookings')->update([
            'final_price' => DB::raw('total_price'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_bookings', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'final_price']);
        });
    }
};
