<?php

namespace App\Application\Facede;

use App\Application\DTO\Transacao\TransacaoDTO;
use App\Domain\Transacao\Entity\Transacao;
use App\Domain\Transacao\Services\TransacaoService;

class TransacaoFacede
{
    public function __construct(private readonly TransacaoService $transacaoService)
    {
    }

    public function transferencia(TransacaoDTO $transacaoDTO): Transacao
    {
        return $this->transacaoService->transferencia($transacaoDTO);
    }

    public function depositar(TransacaoDTO $transacaoDTO): Transacao
    {
        return $this->transacaoService->depositar($transacaoDTO);
    }
}
