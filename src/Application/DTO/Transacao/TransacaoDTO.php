<?php

namespace App\Application\DTO\Transacao;

use Symfony\Component\Validator\Constraints as Assert;

class TransacaoDTO
{
    #[Assert\NotNull(message: 'O campo cpfCnpjRemetente é obrigatório')]
    public string $cpfCnpjRemetente;
    #[Assert\NotNull(message: 'O campo valor é obrigatório')]
    public float $valor;
    #[Assert\NotNull(message: 'O campo cpfCnpjDestinatario é obrigatório')]
    public ?string $cpfCnpjDestinatario;
}
