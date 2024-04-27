<?php

namespace App\Application\DTO\Transacao;

use Symfony\Component\Validator\Constraints as Assert;

class TransacaoDTO
{
    #[Assert\NotNull(message: "O campo cpfCnpjRemetente é obrigatório")]
    public $cpfCnpjRemetente;
    #[Assert\NotNull(message: "O campo valor é obrigatório")]
    public $valor;
    #[Assert\NotNull(message: "O campo cpfCnpjDestinatario é obrigatório", groups: ["deposito"])]
    public $cpfCnpjDestinatario;
}
