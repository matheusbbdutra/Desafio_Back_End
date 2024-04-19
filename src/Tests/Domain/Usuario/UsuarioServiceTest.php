<?php

namespace App\Tests\Domain\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Domain\Transacao\Services\CarteiraService;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Repository\UsuarioRepository;
use App\Domain\Usuario\Services\UsuarioService;
use App\Domain\Usuario\ValueObject\Documento;
use App\Domain\Usuario\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioServiceTest extends TestCase
{
    private UsuarioService $usuarioService;
    private UserPasswordHasherInterface $encoder;
    private UsuarioRepository $usuarioRepository;
    private CarteiraService $carteiraService;

    protected function setUp(): void
    {
        $this->encoder = $this->createMock(UserPasswordHasherInterface::class);
        $this->usuarioRepository = $this->createMock(UsuarioRepository::class);
        $this->carteiraService = $this->createMock(CarteiraService::class);

        $this->usuarioService = new UsuarioService(
            $this->encoder,
            $this->usuarioRepository,
            $this->carteiraService
        );
    }

    public function testCreateUser(): void
    {
        $nome = 'Test User';
        $cpfCnpj = '12345678901';
        $senha = 'password';
        $email = 'test@example.com';
        $isLogista = false;

        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->nome = $nome;
        $usuarioDTO->cpfCnpj = $cpfCnpj;
        $usuarioDTO->senha = $senha;
        $usuarioDTO->email = $email;
        $usuarioDTO->isLogista = $isLogista;

        $this->usuarioService->criarUsuario($usuarioDTO);
    }
}