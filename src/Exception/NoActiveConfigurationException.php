<?php

namespace MessageBus\Exception;

class NoActiveConfigurationException extends \RuntimeException
{
    public function __construct(?string $name = null)
    {
        if ($name) {
            parent::__construct("No active configuration loaded for {$name}.");
            return;
        }

        parent::__construct("No active configuration loaded.");
    }
}
