<?php

namespace App\Infrastructure\Service;

use App\Infrastructure\Messaging\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageService
{
    public function __construct(private MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function sendMessage(?string $to, string $subject, string $body): void
    {
        if (! $to) {
            return;
        }

        $message = new EmailMessage($to, $subject, $body);
        $this->messageBus->dispatch($message);
    }
}
