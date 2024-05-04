<?php

namespace App\Domain\Usuario\ValueObject;

class Email
{
    public function __construct(private string $email)
    {
        if (! $this->isValid($email)) {
            throw new \InvalidArgumentException('Email informado é inválido.');
        }

        $this->email = $email;
    }

    public function isValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
