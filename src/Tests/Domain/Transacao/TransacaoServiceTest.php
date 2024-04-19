<?php

namespace App\Tests\Domain\Transacao;

use App\Application\DTO\Transacao\TransacaoDTO;
use App\Domain\Transacao\Entity\Carteira;
use App\Domain\Transacao\Entity\Transacao;
use App\Domain\Transacao\Enums\Status;
use App\Domain\Transacao\Services\TransacaoService;
use App\Domain\Usuario\Entity\Usuario;
use App\Domain\Usuario\Repository\UsuarioRepository;
use App\Infrastructure\Service\ClientService;
use App\Infrastructure\Service\EmailConsumer;
use App\Infrastructure\Service\MessageBroker;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TransacaoServiceTest extends TestCase
{
    private TransacaoService $transacaoService;
    private MessageBroker $messageBroker;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->usuarioRepository = $this->createMock(UsuarioRepository::class);
        $this->client = $this->createMock(ClientService::class);
        $this->emailConsumer = $this->createMock(EmailConsumer::class);
        $this->messageBroker = $this->createMock(MessageBroker::class);

        $this->transacaoService = new TransacaoService(
            $this->entityManager,
            $this->usuarioRepository,
            $this->client,
            $this->emailConsumer,
            $this->messageBroker
        );
    }

    public function testNotificarStatusTransacao(): void
    {
        $transacao = $this->createMock(Transacao::class);
        $status = Status::Concluido;

        $this->client->expects($this->any())
            ->method('shouldSendMensage')
            ->willReturn(true);

        $this->messageBroker->expects($this->once())
            ->method('sendMessage')
            ->with($this->equalTo('email_queue'), $this->isType('string'));

        $this->transacaoService->notificarStatusEmail($transacao, $status);
    }

    public function testNotificarStatusTransacaoFalhou(): void
    {
        $transacao = $this->createMock(Transacao::class);
        $status = Status::Falhou;

        $this->client->expects($this->any())
            ->method('shouldSendMensage')
            ->willReturn(true);

        $this->messageBroker->expects($this->once())
            ->method('sendMessage')
            ->with($this->equalTo('email_queue'), $this->isType('string'));

        $this->transacaoService->notificarStatusEmail($transacao, $status);
    }

    public function depositarDeveIncrementarSaldoQuandoValorValido(): void
    {
        $transacao = $this->createMock(Transacao::class);
        $transacao->method('getValor')->willReturn(100);

        $this->transacaoService->depositar($transacao);

        $this->assertEquals(100, $this->transacaoService->getSaldo());
    }

    public function depositarNaoDeveIncrementarSaldoQuandoValorInvalido(): void
    {
        $transacao = $this->createMock(Transacao::class);
        $transacao->method('getValor')->willReturn(-100);

        $this->transacaoService->depositar($transacao);

        $this->assertEquals(0, $this->transacaoService->getSaldo());
    }

    public function depositarDeveLancarExcecaoQuandoTransacaoNula(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->transacaoService->depositar(null);
    }

    public function testTransferencia(): void
    {
        $transacaoDTO = new TransacaoDTO();
        $transacaoDTO->cpfCnpjRemetente = 'cpfCnpjRemetente';
        $transacaoDTO->cpfCnpjDestinatario = 'cpfCnpjDestinatario';
        $transacaoDTO->valor = 100.0;

        $remetente = $this->createMock(Usuario::class);
        $destinatario = $this->createMock(Usuario::class);

        $carteiraRemetente = $this->createMock(Carteira::class);
        $carteiraDestinatario = $this->createMock(Carteira::class);

        $carteiraRemetente->method('setSaldo')->willReturnOnConsecutiveCalls(100.0);
        $carteiraRemetente->method('getSaldo')->willReturnOnConsecutiveCalls(100.0);
        $carteiraDestinatario->method('getSaldo')->willReturnOnConsecutiveCalls(100.0);

        $remetente->method('getCarteira')->willReturn($carteiraRemetente);
        $destinatario->method('getCarteira')->willReturn($carteiraDestinatario);

        $this->usuarioRepository->method('findOneBy')->willReturnOnConsecutiveCalls($remetente, $destinatario);

        $this->client->expects($this->any())
            ->method('checkAuthorizationTransaction')
            ->willReturn(true);

        $this->transacaoService->transferencia($transacaoDTO);

        $this->assertEquals(0.0, $remetente->getCarteira()->getSaldo());
        $this->assertEquals(100.0, $destinatario->getCarteira()->getSaldo());
    }
}