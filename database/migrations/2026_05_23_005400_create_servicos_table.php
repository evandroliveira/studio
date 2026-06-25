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
        $this->createTableIfMissing('servicos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
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
