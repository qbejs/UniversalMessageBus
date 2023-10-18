<?php

namespace MessageBus\Middleware;

use MessageBus\Middleware;

class MiddlewareManager
{
    private array $middlewares = [];

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function process(array $message): array
    {
        foreach ($this->middlewares as $middleware) {
            $message = $middleware->handle($message);
        }
        return $message;
    }
}
