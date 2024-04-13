<?php

namespace App\Application\Handler\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;

class CriarUsuarioRequest
{
    public function __construct(private UsuarioDTO $dadosUsuario)
    {
    }

    public function getDadosUsuario(): UsuarioDTO
    {
        return $this->dadosUsuario;
    }
}
