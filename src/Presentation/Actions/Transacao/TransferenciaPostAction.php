<?php

namespace App\Presentation\Actions\Transacao;

use App\Application\DTO\Transacao\TransacaoDTO;
use App\Application\Validators\Validator;
use App\Domain\Transacao\Services\TransacaoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransferenciaPostAction
{
    public function __construct(
        private readonly TransacaoService $service,
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator,
    ) {
    }

    #[Route('/transferencia', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        try {
            $transacaoDTO = $this->serializer->deserialize($request->getContent(), TransacaoDTO::class, 'json');
            $this->validator->validate($transacaoDTO);

            $resultado = $this->service->transferencia($transacaoDTO);

            return new JsonResponse(
                [
                'message' => "A transferência de R$ {$resultado->getValor()} para ".
                "{$resultado->getDestinatario()?->getNome()} foi realizada com sucesso!",
            ],
                Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
