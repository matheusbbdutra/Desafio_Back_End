<?php

namespace App\Tests\Domain\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Domain\Transacao\Services\CarteiraService;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Repository\UsuarioRepository;
use App\Domain\Usuario\Services\UsuarioService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioServiceTest extends TestCase
{
    private UserPasswordHasherInterface $encoder;
    private UsuarioRepository $usuarioRepository;
    private CarteiraService $carteiraService;
    private UsuarioService $usuarioService;

    protected function setUp(): void
    {
        $this->encoder = $this->createMock(UserPasswordHasherInterface::class);
        $this->usuarioRepository = $this->createMock(UsuarioRepository::class);
        $this->carteiraService = $this->createMock(CarteiraService::class);

        $this->usuarioService = new UsuarioService(
            $this->encoder,
            $this->usuarioRepository,
            $this->carteiraService,
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCriarUsuario(): void
    {
        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->nome = 'John Doe';
        $usuarioDTO->email = 'john@example.com';
        $usuarioDTO->cpfCnpj = '95899784016';
        $usuarioDTO->senha = 'password';
        $usuarioDTO->isLogista = true;

        $this->encoder->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(Usuario::class),
                $this->equalTo($usuarioDTO->senha),
            )
            ->willReturn('senha_criptografada');

        $result = $this->usuarioService->criarUsuario($usuarioDTO);

        $this->assertInstanceOf(Usuario::class, $result);
        $this->assertEquals($usuarioDTO->nome, $result->getNome());
        $this->assertEquals($usuarioDTO->email, $result->getEmail()->getEmail());
        $this->assertEquals($usuarioDTO->cpfCnpj, $result->getCpfCnpj());
        $this->assertEquals($usuarioDTO->isLogista, $result->isLogista());
        $this->assertEquals('senha_criptografada', $result->getSenha());
    }

    public function testAtualizarUsuario(): void
    {
        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->id = 1;
        $usuarioDTO->nome = 'John Doe';
        $usuarioDTO->email = 'john@example.com';
        $usuarioDTO->cpfCnpj = '95899784016';
        $usuarioDTO->senha = 'password';
        $usuarioDTO->isLogista = true;

        $this->encoder->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(Usuario::class),
                $this->equalTo($usuarioDTO->senha),
            )
            ->willReturn('senha_criptografada');

        $result = $this->usuarioService->criarUsuario($usuarioDTO);

        $this->assertInstanceOf(Usuario::class, $result);
        $this->assertEquals($usuarioDTO->nome, $result->getNome());
        $this->assertEquals($usuarioDTO->email, $result->getEmail()->getEmail());
        $this->assertEquals($usuarioDTO->cpfCnpj, $result->getCpfCnpj());
        $this->assertEquals($usuarioDTO->isLogista, $result->isLogista());
        $this->assertEquals('senha_criptografada', $result->getSenha());
    }
}
