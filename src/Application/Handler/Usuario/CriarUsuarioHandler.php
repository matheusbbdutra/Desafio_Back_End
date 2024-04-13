<?php

namespace App\Application\Handler\Usuario;

use App\Application\Handler\AbstractHandler;
use App\Application\Validators\Usuario\UsuarioValidator;
use App\Domain\Usuario\Services\UsuarioService;

class CriarUsuarioHandler extends AbstractHandler
{
    public function __construct(private UsuarioValidator $usuarioValidator, private UsuarioService $usuarioService)
    {
    }

    public function handle($request): ?string
    {
        if ($request instanceof CriarUsuarioRequest) {
            $this->usuarioValidator->validate($request->getDadosUsuario());
            $usuario = $this->usuarioService->criarUsuario($request->getDadosUsuario());

            return "Usuário criado com sucesso: " . $usuario->getNome();
        } else {
            return parent::handle($request);
        }
    }
}
