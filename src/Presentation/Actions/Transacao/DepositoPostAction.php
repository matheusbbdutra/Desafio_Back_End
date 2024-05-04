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

class DepositoPostAction
{
    public function __construct(
        private readonly TransacaoService $service,
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator,
    ) {
    }

    #[Route('/deposito', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        try {
            $transacaoDTO = $this->serializer->deserialize($request->getContent(), TransacaoDTO::class, 'json');
            $this->validator->validate($transacaoDTO, ['deposito']);

            $resultado = $this->service->depositar($transacaoDTO);

            return new JsonResponse(
                [
                'message' => "{$resultado->getRemetente()->getNome()}, o depósito de R$".
                "{$resultado->getValor()} foi realizado com sucesso!",
            ],
                Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
