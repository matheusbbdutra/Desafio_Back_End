<?php

namespace App\Application\Handler\Transacao;

use App\Application\DTO\Trasacao\TransacaoDTO;

class TransferenciaTransacaoRequest
{
    private TransacaoDTO $dadosTransacao;

    public function __construct(TransacaoDTO $dadosTransacao)
    {
        $this->dadosTransacao = $dadosTransacao;
    }

    public function getDadosTransacao(): TransacaoDTO
    {
        return $this->dadosTransacao;
    }
}
