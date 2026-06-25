<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createTableIfMissing('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('data');
            $table->time('horario');
            $table->string('servico');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }

    private function createTableIfMissing(string $table, \Closure $callback): void
    {
        if (Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::create($table, $callback);
        } catch (QueryException $e) {
            if (! $this->isDuplicateTableError($e)) {
                throw $e;
            }
        }
    }

    private function isDuplicateTableError(QueryException $e): bool
    {
        return $e->getCode() === '42S01'
            || str_contains(strtolower($e->getMessage()), 'already exists');
    }
};
