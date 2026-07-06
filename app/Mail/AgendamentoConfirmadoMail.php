<?php

namespace App\Mail;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgendamentoConfirmadoMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Agendamento $agendamento)
    {
        $this->agendamento->loadMissing(['user', 'servicoModel', 'funcionario']);
    }

    public function build(): self
    {
        return $this
            ->subject('Seu agendamento foi confirmado')
            ->view('emails.agendamento-confirmado');
    }
}