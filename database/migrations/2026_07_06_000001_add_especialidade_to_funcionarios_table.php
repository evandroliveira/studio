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
        if (! Schema::hasTable('funcionarios') || Schema::hasColumn('funcionarios', 'especialidade')) {
            return;
        }

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->string('especialidade')->nullable()->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('funcionarios') || ! Schema::hasColumn('funcionarios', 'especialidade')) {
            return;
        }

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->dropColumn('especialidade');
        });
    }
};