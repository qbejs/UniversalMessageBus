<?php

namespace MessageBus\Middleware;

interface MiddlewareInterface
{
    public function handle($message);
}
