<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member_memberships', function (Blueprint $table) {
            // drop old FK (defaults to members)
            $table->dropConstrainedForeignId('member_id');

            // create FK to users table
            $table->foreignId('member_id')
                ->constrained('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('member_memberships', function (Blueprint $table) {
            $table->dropConstrainedForeignId('member_id');

            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();
        });
    }
};
