<?php

namespace App\Infrastructure\Messaging\Handler;

use App\Infrastructure\Messaging\EmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailMessageHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(EmailMessage $message): void
    {
        $email = (new Email())
            ->from('matheus@matheusdutra.me')
            ->to($message->getTo())
            ->subject($message->getSubject())
            ->text($message->getBody());

        $this->mailer->send($email);
    }
}
