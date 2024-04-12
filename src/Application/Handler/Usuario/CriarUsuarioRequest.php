<?php

namespace App\Application\Handler\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;

class CriarUsuarioRequest
{
    private UsuarioDTO $dadosUsuario;

    public function __construct(UsuarioDTO $dadosUsuario)
    {
        $this->dadosUsuario = $dadosUsuario;
    }

    public function getDadosUsuario(): UsuarioDTO
    {
        return $this->dadosUsuario;
    }
}
