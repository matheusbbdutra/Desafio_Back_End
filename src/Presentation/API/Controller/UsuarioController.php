<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Application\Handler\AbstractHandler;
use App\Application\Handler\Usuario\AtualizarUsuarioRequest;
use App\Application\Handler\Usuario\CriarUsuarioRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsuarioController extends AbstractController
{
    public function __construct(private AbstractHandler $handlerChain)
    {
    }

    #[Route('/criar-usuario', methods:"POST")]
    public function criarUsuarioAction(Request $request): Response
    {
        try {
            $dados = json_decode($request->getContent(), true);

            $usuarioDTO = new UsuarioDTO();
            $usuarioDTO->nome = $dados['nome'];
            $usuarioDTO->cpfCnpj = $dados['cpfCnpj'];
            $usuarioDTO->email = $dados['email'];
            $usuarioDTO->senha = $dados['senha'];
            $usuarioDTO->isLogista = $dados['isLogista'];

            $usuarioRequest = new CriarUsuarioRequest($usuarioDTO);
            $resultado = $this->handlerChain->handle($usuarioRequest);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/atualizar-usuario/{id}', methods:"PUT")]
    public function atualizarUsuarioAction(Request $request, int $id)
    {
        try {
            $dados = json_decode($request->getContent(), true);

            $usuarioDTO = new UsuarioDTO();
            $usuarioDTO->id =  $id;
            $usuarioDTO->nome = $dados['nome'];
            $usuarioDTO->cpfCnpj = $dados['cpfCnpj'];
            $usuarioDTO->email = $dados['email'];
            $usuarioDTO->senha = $dados['senha'];
            $usuarioDTO->isLogista =  $dados['isLogista'];

            $usuarioRequest = new AtualizarUsuarioRequest($usuarioDTO);
            $resultado = $this->handlerChain->handle($usuarioRequest);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
