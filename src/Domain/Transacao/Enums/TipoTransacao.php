<?php

namespace App\Domain\Transacao\Enums;

enum TipoTransacao: string
{
    case Transferencia = 'Transerência';
    case Deposito = 'Deposito';
}
