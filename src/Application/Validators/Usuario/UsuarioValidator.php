<?php

namespace App\Application\Validators\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;
use http\Exception\InvalidArgumentException;

class UsuarioValidator
{
    public function validate(UsuarioDTO $usuarioDTO): void
    {
        if (empty($usuarioDTO->nome)) {
            throw new InvalidArgumentException('Não foi informado o nome para o usuário.');
        }

        if (empty($usuarioDTO->cpfCnpj)) {
            throw new InvalidArgumentException('Não foi informado o CPF/CNPJ para o usuário.');
        }

        if (empty($usuarioDTO->email)) {
            throw new InvalidArgumentException('Não foi informado o email para o usuário.');
        }

        if (empty($usuarioDTO->senha)) {
            throw new InvalidArgumentException('Não foi informado o senha para o usuário.');
        }
    }
}
