<?php

namespace MessageBus\Exception;

class MessageDispatchFailedException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct("Operation failed: {$message}");
    }
}
