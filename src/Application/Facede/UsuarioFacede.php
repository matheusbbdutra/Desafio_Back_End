<?php
namespace App\Application\Facede;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Services\UsuarioService;

class UsuarioFacede
{
    public function __construct(private readonly UsuarioService $usuarioService)
    {
    }

    public function criarUsuario(UsuarioDTO $usuarioDTO): Usuario
    {
        return $this->usuarioService->criarUsuario($usuarioDTO);
    }

    public function atualizarUsuario(UsuarioDTO $usuarioDTO): void
    {
        $this->usuarioService->atualizarUsuario($usuarioDTO);
    }
}