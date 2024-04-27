<?php

declare(strict_types=1);

namespace App\Presentation\Controller\API;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Application\Facede\UsuarioFacede;
use App\Application\Validators\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


class UsuarioController extends AbstractController
{
    public function __construct(
        private UsuarioFacede       $facede,
        private SerializerInterface $serializer,
        private  Validator          $validator
    ) {
    }

    #[Route('/criar-usuario', methods:"POST")]
    public function criarUsuarioAction(Request $request): Response
    {
        try {
            $usuarioDTO = $this->serializer->deserialize($request->getContent(), UsuarioDTO::class, 'json');
            $this->validator->validate($usuarioDTO);
            $resultado = $this->facede->criarUsuario($usuarioDTO);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {

            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/atualizar-usuario/{id}', methods:"PUT")]
    public function atualizarUsuarioAction(Request $request, int $id)
    {
        try {
            $usuarioDTO = $this->serializer->deserialize($request->getContent(), UsuarioDTO::class, 'json');
            $usuarioDTO->id = $id;
            $this->validator->validate($usuarioDTO, ['update']);
            $this->facede->atualizarUsuario($usuarioDTO);

            return new JsonResponse(['message' => 'Usuário atualizado com sucesso!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
