<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Application\DTO\Trasacao\TransacaoDTO;
use App\Application\Handler\AbstractHandler;
use App\Application\Handler\Transacao\DepositoTransacaoRequest;
use App\Application\Handler\Transacao\TransferenciaTransacaoRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class TransacaoController extends AbstractController
{
    public function __construct(private AbstractHandler $handlerChain)
    {
    }

    #[Route('/transferencia', methods: 'POST')]
    public function transferenciaAction(Request $request): Response
    {
        try {
            $dados = json_decode($request->getContent(), true);

            $transacaoDTO = new TransacaoDTO();
            $transacaoDTO->cpfCnpjRemetente = $dados['cpfCnpjRemetente'];
            $transacaoDTO->cpfCnpjDestinatario = $dados['cpfCnpjDestinatario'];
            $transacaoDTO->valor = $dados['valor'];

            $transferenciaReponse = new TransferenciaTransacaoRequest($transacaoDTO);
            $resultado = $this->handlerChain->handle($transferenciaReponse);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/deposito', methods: 'POST')]
    public function depositoAction(Request $request): Response
    {
        try {
            $dados = json_decode($request->getContent(), true);

            $transacaoDTO = new TransacaoDTO();
            $transacaoDTO->cpfCnpjRemetente = $dados['cpfCnpjRemetente'];
            $transacaoDTO->valor = $dados['valor'];

            $transferenciaReponse = new DepositoTransacaoRequest($transacaoDTO);
            $resultado = $this->handlerChain->handle($transferenciaReponse);

            return new JsonResponse(['message' => $resultado], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
