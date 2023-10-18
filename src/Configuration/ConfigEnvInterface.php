<?php

namespace MessageBus\Configuration;

interface ConfigEnvInterface
{
    public function get(string $key, $default = null);
    public function isSupported(string $name): bool;
}
