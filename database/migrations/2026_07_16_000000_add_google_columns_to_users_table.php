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
        if (! Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasColumn('users', 'google_id') && Schema::hasColumn('users', 'google_avatar')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'google_avatar')) {
                $table->text('google_avatar')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropUnique('users_google_id_unique');
                $table->dropColumn('google_id');
            }

            if (Schema::hasColumn('users', 'google_avatar')) {
                $table->dropColumn('google_avatar');
            }
        });
    }
};