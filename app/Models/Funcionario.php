<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $fillable = [
        'nome',
        'especialidade',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
