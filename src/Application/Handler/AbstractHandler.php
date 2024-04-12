<?php

namespace App\Application\Handler;

class AbstractHandler
{
    private $nextHandler;

    public function setNext(AbstractHandler $handler): AbstractHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle($request): ?string
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }

        return null;
    }
}
