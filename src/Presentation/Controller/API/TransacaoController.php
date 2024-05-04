<?php

namespace App\Presentation\Controller\API;

use App\Application\DTO\Transacao\TransacaoDTO;
use App\Application\Validators\Validator;
use App\Domain\Transacao\Services\TransacaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class TransacaoController extends AbstractController
{
    public function __construct(
        private readonly TransacaoService $service,
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator
    ) {
    }

    #[Route('/transferencia', methods: 'POST')]
    public function transferenciaAction(Request $request): Response
    {
        try {
            $transacaoDTO = $this->serializer->deserialize($request->getContent(), TransacaoDTO::class, 'json');
            $this->validator->validate($transacaoDTO);

            $resultado = $this->service->transferencia($transacaoDTO);

            return new JsonResponse([
                'message' => "A transferência de R$ {$resultado->getValor()} para " .
                "{$resultado->getDestinatario()->getNome()} foi realizada com sucesso!"
                ], 
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/deposito', methods: 'POST')]
    public function depositoAction(Request $request): Response
    {
        try {
            $transacaoDTO = $this->serializer->deserialize($request->getContent(), TransacaoDTO::class, 'json');
            $this->validator->validate($transacaoDTO, ['deposito']);

            $resultado = $this->service->depositar($transacaoDTO);

            return new JsonResponse([
                'message' => "{$resultado->getRemetente()->getNome()}, o depósito de R$" .
                "{$resultado->getValor()} foi realizado com sucesso!"
                ], 
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
