<?php

namespace App\Application\Handler\Transacao;

use App\Application\DTO\Trasacao\TransacaoDTO;

class TransferenciaTransacaoRequest
{

    public function __construct(private TransacaoDTO $dadosTransacao)
    {
    }

    public function getDadosTransacao(): TransacaoDTO
    {
        return $this->dadosTransacao;
    }
}
