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

class AtualizarUsuarioPutAction
{
    public function __construct(
        private readonly UsuarioService $service,
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator,
    ) {
    }

    #[Route('/atualizar-usuario/{id}', methods: 'PUT')]
    public function __invoke(Request $request, int $id): Response
    {
        try {
            $usuarioDTO = $this->serializer->deserialize($request->getContent(), UsuarioDTO::class, 'json');
            $usuarioDTO->id = $id;
            $this->validator->validate($usuarioDTO, ['update']);
            $resultado = $this->service->atualizarUsuario($usuarioDTO);

            return new JsonResponse(
                [
                'message' => "Usuário {$resultado->getNome()} atualizado com sucesso!",
            ],
                Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
