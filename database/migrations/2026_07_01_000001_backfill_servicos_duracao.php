<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('servicos') && Schema::hasColumn('servicos', 'duracao')) {
            DB::table('servicos')
                ->whereNull('duracao')
                ->update(['duracao' => '00:30:00']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('servicos') && Schema::hasColumn('servicos', 'duracao')) {
            DB::table('servicos')
                ->where('duracao', '00:30:00')
                ->update(['duracao' => null]);
        }
    }
};