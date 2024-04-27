<?php

namespace App\Domain\Usuario\Services;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Domain\Transacao\Services\CarteiraService;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Repository\UsuarioRepository;
use App\Domain\Usuario\ValueObject\Documento;
use App\Domain\Usuario\ValueObject\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioService
{
    public function __construct(
        private UserPasswordHasherInterface $encoder,
        private UsuarioRepository $usuarioRepository,
        private CarteiraService $carteiraService
    ) {
    }

    public function criarUsuario(UsuarioDTO $usuarioDTO): Usuario
    {
        $usuario = new Usuario();
        $usuario->setNome($usuarioDTO->nome);
        $usuario->setEmail(new Email($usuarioDTO->email));
        $usuario->setCpfCnpj(new Documento($usuarioDTO->cpfCnpj));
        $senha = $this->encoder->hashPassword($usuario, $usuarioDTO->senha);
        $usuario->setSenha($senha);
        $usuario->setIsLogista($usuarioDTO->isLogista ?: false);
        $this->usuarioRepository->criarUsuario($usuario);
        $this->carteiraService->criarCarteira($usuario);

        return $usuario;
    }

    public function atualizarUsuario(UsuarioDTO $usuarioDTO): void
    {
        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->find($usuarioDTO->id);
        $usuario->setNome($usuarioDTO->nome);
        $usuario->setEmail(new Email($usuarioDTO->email));
        $usuario->setCpfCnpj(new Documento($usuarioDTO->cpfCnpj));
        $senha = $this->encoder->hashPassword($usuario, $usuarioDTO->senha);
        $usuario->setSenha($senha);
        $usuario->setIsLogista($usuarioDTO->isLogista ?: false);

        $this->usuarioRepository->atualizarUsuario($usuario);
    }
}
