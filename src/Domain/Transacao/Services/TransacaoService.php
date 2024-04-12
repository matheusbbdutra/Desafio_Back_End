<?php

namespace App\Domain\Transacao\Services;

use App\Application\DTO\Trasacao\TransacaoDTO;
use App\Domain\Transacao\Entity\Transacao;
use App\Domain\Transacao\Enums\Status;
use App\Domain\Transacao\Enums\TipoTransacao;
use App\Domain\Transacao\Repository\CarteiraRepository;
use App\Domain\Transacao\Repository\TransacaoRepository;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Repository\UsuarioRepository;
use App\Infrastructure\Service\AutorizacaoClient;
use Doctrine\ORM\EntityManagerInterface;

class TransacaoService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TransacaoRepository $transacaoRepository,
        private UsuarioRepository $usuarioRepository,
        private CarteiraRepository $carteiraRepository,
        private AutorizacaoClient $client
    ) {
    }

    public function transferencia(TransacaoDTO $transacaoDTO)
    {
        $this->entityManager->beginTransaction();

        try {
            /** @var Usuario $remetente */
            $remetente = $this->usuarioRepository->findOneBy(['cpfCnpj' => $transacaoDTO->cpfCnpjRemetente]);

            if ($remetente->isLogista()) {
                throw new \Exception("Logista não pode efetuar transferências.");
            }

            $destinatario = $this->usuarioRepository->findOneBy(['cpfCnpj' => $transacaoDTO->cpfCnpjDestinatario]);

            if ($remetente->getCarteira()->getSaldo() < $transacaoDTO->valor) {
                throw new \Exception("Saldo insuficiente");
            }

            $remetente->getCarteira()->subtrairSaldo($transacaoDTO->valor);
            $destinatario->getCarteira()->adicionarSaldo($transacaoDTO->valor);

            $this->entityManager->persist($remetente);
            $this->entityManager->persist($destinatario);

            $transacao = new Transacao(
                $remetente,
                $destinatario,
                $transacaoDTO->valor,
                Status::Concluido,
                TipoTransacao::Transferencia,
                new \DateTime()
            );
            $this->entityManager->persist($transacao);

            if (!$this->client->checkAuthorization()) {
                throw new \DomainException('Transação não autorizada');
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $transacao = new Transacao(
                $remetente ?? null,
                $destinatario ?? null,
                $transacaoDTO->valor,
                Status::Falhou,
                TipoTransacao::Transferencia,
                new \DateTime()
            );
            $this->entityManager->persist($transacao);
            $this->entityManager->flush();

            throw new \RuntimeException("Erro ao realizar a transferência: " . $e->getMessage(), 0, $e);
        }

        return $transacao;
    }

    public function deposito(TransacaoDTO $transacaoDTO)
    {
        $this->entityManager->beginTransaction();

        try {
            /** @var Usuario $remetente */
            $remetente = $this->usuarioRepository->findOneBy(['cpfCnpj' => $transacaoDTO->cpfCnpjRemetente]);
            $remetente->getCarteira()->adicionarSaldo($transacaoDTO->valor);

            $this->entityManager->persist($remetente);

            $transacao = new Transacao(
                $remetente,
                null,
                $transacaoDTO->valor,
                Status::Concluido,
                TipoTransacao::Deposito,
                new \DateTime()
            );
            $this->entityManager->persist($transacao);

            if (!$this->client->checkAuthorization()) {
                throw new \DomainException('Transação não autorizada');
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $transacao = new Transacao(
                $remetente ?? null,
                null,
                $transacaoDTO->valor,
                Status::Falhou,
                TipoTransacao::Deposito,
                new \DateTime()
            );
            $this->entityManager->persist($transacao);
            $this->entityManager->flush();

            throw new \RuntimeException("Erro ao realizar o deposito: " . $e->getMessage(), 0, $e);
        }

        return $transacao;
    }
}
