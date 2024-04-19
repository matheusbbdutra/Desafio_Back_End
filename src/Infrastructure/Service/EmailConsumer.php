<?php

namespace App\Infrastructure\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailConsumer
{
    public function __construct(
        private MailerInterface $mailer,
        private MessageBroker $messageBroker
    ) {
    }

    public function consume()
    {
        while (true) {
            $message = $this->messageBroker->receive();

            if (empty($message)) {
                break;
            }

            $data = json_decode($message->content, true);

            $email = $data['recipient'];
            $content = $data['message'];

            $emailMessage = (new Email())
                ->from('you@example.com')
                ->to($email)
                ->subject('Message from RabbitMQ')
                ->text($content);

            $this->mailer->send($emailMessage);
            $this->messageBroker->acknowledge($message);
        }
    }
}