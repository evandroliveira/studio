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
        if (! Schema::hasTable('agendamentos') || Schema::hasColumn('agendamentos', 'status')) {
            return;
        }

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('status')->default('pendente')->after('profissional');
        });

        DB::table('agendamentos')
            ->whereNull('status')
            ->update(['status' => 'pendente']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('agendamentos') || ! Schema::hasColumn('agendamentos', 'status')) {
            return;
        }

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};