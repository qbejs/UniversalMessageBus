<?php

namespace MessageBus\Exception;

class MessageDispatchTimeoutException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct("Operation timed out after {$message} milliseconds.");
    }
}
