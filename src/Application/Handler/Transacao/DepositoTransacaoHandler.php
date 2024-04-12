<?php

namespace App\Application\Handler\Transacao;

use App\Application\Handler\AbstractHandler;
use App\Application\Validators\Transacao\TransancaoValidator;
use App\Domain\Transacao\Services\TransacaoService;

class DepositoTransacaoHandler extends AbstractHandler
{
    public function __construct(
        private TransacaoService $transacaoService,
        private TransancaoValidator $validator
    ) {
    }

    public function handle($request): ?string
    {
        if ($request instanceof DepositoTransacaoRequest) {
            $this->validator->validateDeposito($request->getDadosTransacao());
            $transacao = $this->transacaoService->deposito($request->getDadosTransacao());

            return "Deposito " . $transacao->getStatus()->value;
        } else {
            return parent::handle($request);
        }
    }
}
