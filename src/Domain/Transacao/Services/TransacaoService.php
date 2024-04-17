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
        private UsuarioRepository $usuarioRepository,
        private AutorizacaoClient $client,
    ) {
    }

    public function depositar(TransacaoDTO $transacaoDTO): Transacao
    {
        $this->entityManager->beginTransaction();

        try {
            /** @var Usuario $remetente */
            $remetente = $this->getUsuario($transacaoDTO->cpfCnpjRemetente);
            $remetente->getCarteira()->adicionarSaldo($transacaoDTO->valor);

            $this->entityManager->persist($remetente);

            $transacao = $this->criarTransacao(
                $remetente,
                null,
                $transacaoDTO->valor,
                Status::Concluido,
                TipoTransacao::Deposito
            );

            if (!$this->client->checkAuthorization()) {
                throw new \DomainException('Transação não autorizada');
            }

            $this->entityManager->commit();
            return $transacao;
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $this->criarTransacao($remetente, null, $transacaoDTO->valor, Status::Falhou, TipoTransacao::Deposito);
            throw new \RuntimeException("Erro ao realizar o deposito: " . $e->getMessage(), 0, $e);
        }
    }

    public function transferencia(TransacaoDTO $transacaoDTO): Transacao
    {
        $this->entityManager->beginTransaction();

        try {
            $remetente = $this->getUsuario($transacaoDTO->cpfCnpjRemetente);
            $destinatario = $this->getUsuario($transacaoDTO->cpfCnpjDestinatario);

            $this->validarTransferencia($remetente, $transacaoDTO->valor);

            $this->executarTransferencia($remetente, $destinatario, $transacaoDTO->valor);
            $this->entityManager->commit();

            return $this->criarTransacao(
                $remetente,
                $destinatario,
                $transacaoDTO->valor,
                Status::Concluido,
                TipoTransacao::Transferencia
            );
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $this->criarTransacao($remetente, $destinatario, $transacaoDTO->valor, Status::Falhou, TipoTransacao::Transferencia);
            throw new \RuntimeException("Erro ao realizar a transferência: " . $e->getMessage(), 0, $e);
        }


    }

    private function getUsuario(string $cpfCnpj): Usuario
    {
        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->findOneBy(['cpfCnpj' => $cpfCnpj]);

        return $usuario;
    }

    private function validarTransferencia(Usuario $remetente, float $valor): void
    {
        if ($remetente->isLogista()) {
            throw new \Exception("Logista não pode efetuar transferências.");
        }

        if ($remetente->getCarteira()->getSaldo() < $valor) {
            throw new \Exception("Saldo insuficiente");
        }

        if (!$this->client->checkAuthorization()) {
            throw new \DomainException('Transação não autorizada');
        }
    }

    private function executarTransferencia(Usuario $remetente, Usuario $destinatario, float $valor): void
    {
        $remetente->getCarteira()->subtrairSaldo($valor);
        $destinatario->getCarteira()->adicionarSaldo($valor);

        $this->entityManager->persist($remetente);
        $this->entityManager->persist($destinatario);

        $this->entityManager->flush();
    }

    private function criarTransacao(
        Usuario $remetente,
        Usuario $destinatario,
        float $valor, Status
        $status,
        TipoTransacao $tipo
    ): Transacao {
        $transacao = new Transacao($remetente, $destinatario, $valor, $status, $tipo, new \DateTime());

        $this->entityManager->persist($transacao);
        $this->entityManager->flush();

        return $transacao;
    }
}
