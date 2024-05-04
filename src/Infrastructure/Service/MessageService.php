<?php

namespace App\Infrastructure\Service;


use Symfony\Component\Messenger\MessageBusInterface;
use App\Infrastructure\Messaging\EmailMessage;

class MessageService
{
    public function __construct(private MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function sendMessage(string $to, string $subject, string $body)
    {
        $message = new EmailMessage($to, $subject, $body);
        $this->messageBus->dispatch($message);
    }
}
