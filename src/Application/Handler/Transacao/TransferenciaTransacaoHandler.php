<?php

namespace App\Application\Handler\Transacao;

use App\Application\Handler\AbstractHandler;
use App\Application\Validators\Transacao\TransancaoValidator;
use App\Domain\Transacao\Services\TransacaoService;

class TransferenciaTransacaoHandler extends AbstractHandler
{
    public function __construct(
        private TransacaoService $transacaoService,
        private TransancaoValidator $validator
    ) {
    }

    public function handle($request): ?string
    {
        if ($request instanceof TransferenciaTransacaoRequest) {
            $this->validator->validateTransferencia($request->getDadosTransacao());
            $transacao = $this->transacaoService->transferencia($request->getDadosTransacao());

            return "Transferência " . $transacao->getStatus()->value;
        } else {
            return parent::handle($request);
        }
    }
}
