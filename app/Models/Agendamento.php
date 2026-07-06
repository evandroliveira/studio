<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'user_id',
        'servico_id',
        'funcionario_id',
        'data',
        'horario',
        'servico',
        'profissional',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function servicoModel()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}
