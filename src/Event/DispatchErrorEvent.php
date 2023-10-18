<?php

namespace MessageBus\Event;

use MessageBus\Event\Event;

class DispatchErrorEvent extends Event
{
    private \Exception $exception;

    public function __construct($message, \Exception $exception)
    {
        parent::__construct($message);
        $this->exception = $exception;
    }

    public function getException(): \Exception
    {
        return $this->exception;
    }
}
