<?php

namespace App\Application\Handler;

class HandlerChainBuilder
{
    private $handlers;

    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }

    public function buildChain(): AbstractHandler
    {
        $chain = null;
        $previousHandler = null;

        foreach ($this->handlers as $handler) {
            if ($chain === null) {
                $chain = $handler;
            } else {
                $previousHandler->setNext($handler);
            }
            $previousHandler = $handler;
        }

        return $chain;
    }
}
