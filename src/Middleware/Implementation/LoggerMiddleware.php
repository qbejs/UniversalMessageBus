<?php

namespace MessageBus\Middleware\Implementation;

use MessageBus\Middleware\MiddlewareInterface;

class LoggerMiddleware implements MiddlewareInterface
{
    public function handle($message)
    {
        echo "Logged message of type: " . $message['type'] . "\n";
        return $message;
    }
}
