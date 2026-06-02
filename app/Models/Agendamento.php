<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'user_id',
        'data',
        'horario',
        'servico',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
