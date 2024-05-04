<?php

namespace App\Application\DTO\Usuario;

use Symfony\Component\Validator\Constraints as Assert;

class UsuarioDTO
{
    #[Assert\NotNull(message: 'O campo id é obrigatório', groups: ['update'])]
    public int $id;
    #[Assert\NotNull(message: 'O campo nome é obrigatório')]
    public string $nome;
    #[Assert\NotNull(message: 'O campo CPF/CNPJ é obrigatório')]
    public string $cpfCnpj;
    #[Assert\NotNull(message: 'O campo email é obrigatório')]
    public string $email;
    #[Assert\NotNull(message: 'O campo senha é obrigatório')]
    public string $senha;
    #[Assert\NotNull(message: 'O campo isLogista é obrigatório')]
    public bool $isLogista;
}
