<?php
namespace App\Tests\Infrastructure\Services;

use App\Domain\Fila\Entity\ProcessaFilaMensagem;
use App\Infrastructure\Service\ClientService;
use App\Infrastructure\Service\EmailConsumer;
use App\Infrastructure\Service\MessageBroker;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class EmailConsumerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->clientService = $this->createStub(ClientService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->messageBroker = $this->createMock(MessageBroker::class);

        $this->emailConsumer = new EmailConsumer($this->mailer, $this->messageBroker);
    }

    public function testConsume(): void
    {
        $nome = 'email_queue';
        $conteudo = json_encode(['recipient' => 'user@example.com', 'message' => 'Hello, user!']);
        $message = $this->createMock(\Bunny\Message::class);
        $message->content = $conteudo;

        $this->messageBroker->expects($this->exactly(2))
            ->method('receive')
            ->willReturnOnConsecutiveCalls($message, null);

        $this->clientService->expects($this->once())
            ->method('checkEmailInMailHog')
            ->with('user@example.com', 'Hello, user!')
            ->willReturn(true);

        $this->mailer->expects($this->once())
            ->method('send');

        $this->emailConsumer->consume();
    }
}
