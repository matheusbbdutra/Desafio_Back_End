<?php

namespace App\Application\Handler;

class HandlerChainBuilder
{
    public function __construct(private iterable $handlers)
    {
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
