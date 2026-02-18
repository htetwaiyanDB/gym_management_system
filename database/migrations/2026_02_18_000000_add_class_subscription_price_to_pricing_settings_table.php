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
        if (! Schema::hasColumn('pricing_settings', 'class_subscription_price')) {
            Schema::table('pricing_settings', function (Blueprint $table) {
                $table->decimal('class_subscription_price', 10, 2)
                    ->default(70000)
                    ->after('annual_subscription_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pricing_settings', 'class_subscription_price')) {
            Schema::table('pricing_settings', function (Blueprint $table) {
                $table->dropColumn('class_subscription_price');
            });
        }
    }
};
