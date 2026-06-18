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
        $servicosHasTable = Schema::hasTable('servicos');
        $funcionariosHasTable = Schema::hasTable('funcionarios');
        $agendamentosHasTable = Schema::hasTable('agendamentos');

        if ($servicosHasTable) {
            $servicosNeedsNome = ! Schema::hasColumn('servicos', 'nome');
            $servicosNeedsValor = ! Schema::hasColumn('servicos', 'valor');

            if ($servicosNeedsNome || $servicosNeedsValor) {
                Schema::table('servicos', function (Blueprint $table) use ($servicosNeedsNome, $servicosNeedsValor) {
                    if ($servicosNeedsNome) {
                        $table->string('nome')->nullable();
                    }

                    if ($servicosNeedsValor) {
                        $table->decimal('valor', 10, 2)->default(0);
                    }
                });
            }
        }

        if ($funcionariosHasTable && ! Schema::hasColumn('funcionarios', 'nome')) {
            Schema::table('funcionarios', function (Blueprint $table) {
                $table->string('nome')->nullable();
            });
        }

        if ($agendamentosHasTable) {
            if (! Schema::hasColumn('agendamentos', 'servico_id')) {
                Schema::table('agendamentos', function (Blueprint $table) {
                    $table->foreignId('servico_id')->nullable()->constrained('servicos')->nullOnDelete();
                });
            }

            if (! Schema::hasColumn('agendamentos', 'funcionario_id')) {
                Schema::table('agendamentos', function (Blueprint $table) {
                    $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->nullOnDelete();
                });
            }
        }

        if (
            $agendamentosHasTable
            && $servicosHasTable
            && Schema::hasColumn('agendamentos', 'servico')
            && Schema::hasColumn('agendamentos', 'servico_id')
            && Schema::hasColumn('servicos', 'nome')
        ) {
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
        }

        if (
            $agendamentosHasTable
            && $funcionariosHasTable
            && Schema::hasColumn('agendamentos', 'profissional')
            && Schema::hasColumn('agendamentos', 'funcionario_id')
            && Schema::hasColumn('funcionarios', 'nome')
        ) {
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
        }

        if (
            $servicosHasTable
            && Schema::hasColumn('servicos', 'nome')
            && ! Schema::hasIndex('servicos', 'servicos_nome_unique', 'unique')
        ) {
            Schema::table('servicos', function (Blueprint $table) {
                $table->unique('nome');
            });
        }

        if (
            $funcionariosHasTable
            && Schema::hasColumn('funcionarios', 'nome')
            && ! Schema::hasIndex('funcionarios', 'funcionarios_nome_unique', 'unique')
        ) {
            Schema::table('funcionarios', function (Blueprint $table) {
                $table->unique('nome');
            });
        }

        if (
            $agendamentosHasTable
            && Schema::hasColumn('agendamentos', 'data')
            && Schema::hasColumn('agendamentos', 'horario')
            && Schema::hasColumn('agendamentos', 'funcionario_id')
            && ! Schema::hasIndex('agendamentos', 'agendamentos_data_horario_funcionario_unique', 'unique')
        ) {
            Schema::table('agendamentos', function (Blueprint $table) {
                $table->unique(['data', 'horario', 'funcionario_id'], 'agendamentos_data_horario_funcionario_unique');
            });
        }
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
