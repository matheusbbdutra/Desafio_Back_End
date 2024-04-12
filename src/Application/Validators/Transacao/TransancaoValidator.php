<?php

namespace App\Application\Validators\Transacao;

use App\Application\DTO\Trasacao\TransacaoDTO;
use http\Exception\InvalidArgumentException;

class TransancaoValidator
{
    public function validateTransferencia(TransacaoDTO $transacaoDTO): void
    {
        if (empty($transacaoDTO->cpfCnpjRemetente)) {
            throw new InvalidArgumentException('Não foi informado os dados do remetente.');
        }

        if (empty($transacaoDTO->cpfCnpjDestinatario)) {
            throw new InvalidArgumentException('Não foi informado os dados do remetente.');
        }

        if (empty($transacaoDTO->valor)) {
            throw new InvalidArgumentException('Não foi informado o valor da transferência.');
        }
    }

    public function validateDeposito(TransacaoDTO $transacaoDTO): void
    {
        if (empty($transacaoDTO->cpfCnpjRemetente)) {
            throw new InvalidArgumentException('Não foi informado os dados do remetente.');
        }

        if (empty($transacaoDTO->valor)) {
            throw new InvalidArgumentException('Não foi informado o valor da transferência.');
        }
    }
}
