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
        Schema::table('servicos', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('id');
            $table->decimal('valor', 10, 2)->default(0)->after('nome');
        });

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('id');
        });

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreignId('servico_id')->nullable()->after('user_id')->constrained('servicos')->nullOnDelete();
            $table->foreignId('funcionario_id')->nullable()->after('servico_id')->constrained('funcionarios')->nullOnDelete();
        });

        DB::table('agendamentos')
            ->whereNotNull('servico')
            ->select('id', 'servico')
            ->orderBy('id')
            ->chunkById(100, function ($agendamentos) {
                foreach ($agendamentos as $agendamento) {
                    if (! $agendamento->servico) {
                        continue;
                    }

                    $servicoId = DB::table('servicos')->where('nome', $agendamento->servico)->value('id');

                    if (! $servicoId) {
                        $servicoId = DB::table('servicos')->insertGetId([
                            'nome' => $agendamento->servico,
                            'valor' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('agendamentos')->where('id', $agendamento->id)->update(['servico_id' => $servicoId]);
                }
            });

        DB::table('agendamentos')
            ->whereNotNull('profissional')
            ->select('id', 'profissional')
            ->orderBy('id')
            ->chunkById(100, function ($agendamentos) {
                foreach ($agendamentos as $agendamento) {
                    if (! $agendamento->profissional) {
                        continue;
                    }

                    $funcionarioId = DB::table('funcionarios')->where('nome', $agendamento->profissional)->value('id');

                    if (! $funcionarioId) {
                        $funcionarioId = DB::table('funcionarios')->insertGetId([
                            'nome' => $agendamento->profissional,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('agendamentos')->where('id', $agendamento->id)->update(['funcionario_id' => $funcionarioId]);
                }
            });

        Schema::table('servicos', function (Blueprint $table) {
            $table->unique('nome');
        });

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->unique('nome');
        });

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->unique(['data', 'horario', 'funcionario_id'], 'agendamentos_data_horario_funcionario_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropUnique('agendamentos_data_horario_funcionario_unique');
            $table->dropConstrainedForeignId('funcionario_id');
            $table->dropConstrainedForeignId('servico_id');
        });

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->dropUnique(['nome']);
            $table->dropColumn('nome');
        });

        Schema::table('servicos', function (Blueprint $table) {
            $table->dropUnique(['nome']);
            $table->dropColumn(['nome', 'valor']);
        });
    }
};
