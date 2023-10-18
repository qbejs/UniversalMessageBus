<?php

namespace MessageBus\Configuration;

use MessageBus\Exception\NoActiveConfigurationException;

class ConfigurationManager
{
    private array $configProviders = [];
    private ?ConfigEnvInterface $activeConfig = null;

    public function __construct(array $configProviders)
    {
        $this->configProviders = $configProviders;
    }

    public function loadConfiguration(string $name): void
    {
        foreach ($this->configProviders as $provider) {
            if ($provider->isSupported($name)) {
                $this->activeConfig = $provider;
                return;
            }
        }

        throw new NoActiveConfigurationException($name);
    }

    public function getActiveConfig(): ?ConfigEnvInterface
    {
        return $this->activeConfig;
    }
}
