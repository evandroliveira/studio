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
        if (Schema::hasTable('servicos') && ! Schema::hasColumn('servicos', 'duracao')) {
            Schema::table('servicos', function (Blueprint $table) {
                $table->time('duracao')->nullable()->after('valor');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('servicos') && Schema::hasColumn('servicos', 'duracao')) {
            Schema::table('servicos', function (Blueprint $table) {
                $table->dropColumn('duracao');
            });
        }
    }
};