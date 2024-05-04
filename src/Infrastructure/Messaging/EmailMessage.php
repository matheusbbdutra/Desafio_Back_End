<?php

namespace App\Infrastructure\Messaging;

class EmailMessage
{
    public function __construct(private string $to, private string $subject, private string $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
