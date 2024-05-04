<?php

namespace App\Presentation\Actions\Usuario;

use App\Application\DTO\Usuario\UsuarioDTO;
use App\Application\Validators\Validator;
use App\Domain\Usuario\Services\UsuarioService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CriarUsuarioPostAction
{
    public function __construct(
        private readonly UsuarioService $service,
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator,
    ) {
    }

    #[Route('/criar-usuario', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        try {
            $usuarioDTO = $this->serializer->deserialize($request->getContent(), UsuarioDTO::class, 'json');
            $this->validator->validate($usuarioDTO);
            $resultado = $this->service->criarUsuario($usuarioDTO);

            return new JsonResponse(
                [
                'message' => "Usuário {$resultado->getNome()} criado com sucesso.",
            ],
                Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
