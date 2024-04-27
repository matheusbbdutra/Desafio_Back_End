<?php

declare(strict_types=1);

namespace App\Presentation\Controller\API;

use App\Application\DTO\Transacao\TransacaoDTO;
use App\Application\Facede\TransacaoFacede;
use App\Application\Validators\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class TransacaoController extends AbstractController
{
    public function __construct(
        private  TransacaoFacede    $facede,
        private SerializerInterface $serializer,
        private Validator           $validator
    ) {
    }

    #[Route('/transferencia', methods: 'POST')]
    public function transferenciaAction(Request $request): Response
    {
        try {
            $transacaoDTO = $this->serializer->deserialize($request->getContent(), TransacaoDTO::class, 'json');
            $this->validator->validate($transacaoDTO);

            $resultado = $this->facede->transferencia($transacaoDTO);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
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

            $resultado = $this->facede->depositar($transacaoDTO);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
