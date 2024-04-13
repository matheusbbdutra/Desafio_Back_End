<?php

namespace App\Application\Handler\Usuario;

use App\Application\Handler\AbstractHandler;
use App\Domain\Usuario\Services\UsuarioService;

class AtualizarUsuarioHandler extends AbstractHandler
{
    public function __construct(private UsuarioService $usuarioService)
    {
    }

    public function handle($request): ?string
    {
        if ($request instanceof AtualizarUsuarioRequest) {
            $usuario = $this->usuarioService->atualizarUsuario($request->getDadosUsuario());

            return "Usuário atualizado com sucesso: " . $usuario->getNome();
        } else {
            return parent::handle($request);
        }
    }
}
