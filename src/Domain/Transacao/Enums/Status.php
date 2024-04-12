<?php

namespace App\Domain\Transacao\Enums;

enum Status: string
{
    case Pendente = 'pendente';
    case Concluido = 'concluido';
    case Falhou = 'falhou';
}
