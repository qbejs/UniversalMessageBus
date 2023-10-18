<?php

namespace MessageBus;

use MessageBus\Adapter\Queue\QueueAdapter;
use MessageBus\Configuration\ConfigEnvInterface;
use MessageBus\Configuration\ConfigurationManager;
use MessageBus\Exception\MessageDispatchFailedException;
use MessageBus\Exception\MessageDispatchTimeoutException;
use MessageBus\Exception\UnsetQueueAdapter;

class MessageDispatcher
{
    private int $maxRetries;
    private int $delay;
    private int $timeout;
    private ?QueueAdapter $adapter = null;
    private ConfigEnvInterface $configManager;

    public function __construct(ConfigEnvInterface $config)
    {
        $this->configManager = $config;
        $this->maxRetries = $this->configManager->get('MAX_RETRIES', 3);
        $this->delay = $this->configManager->get('DELAY', 1000);
        $this->timeout = $this->configManager->get('TIMEOUT', 10000);
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function setMaxRetries(int $maxRetries): void
    {
        $this->maxRetries = $maxRetries;
    }

    public function setDelay(int $delay): void
    {
        $this->delay = $delay;
    }

    public function setAdapter(QueueAdapter $adapter): void
    {
        $this->adapter = $adapter;
    }

    public function dispatch(string $serializedMessage): ?array
    {
        try {
            if (!$this->adapter) {
                throw new UnsetQueueAdapter();
            }

            $this->adapter->enqueue($serializedMessage);

            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw new MessageDispatchFailedException($e->getMessage());
        }
    }

    public function withRetry(string $serializedMessage, int $timeout = null): ?array
    {
        $attempts = 0;
        $startTime = microtime(true);

        while ($attempts < $this->maxRetries) {
            try {
                return $this->dispatch($serializedMessage);
            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $this->maxRetries) {
                    throw $e;
                }
                usleep($this->delay);

                if ((microtime(true) - $startTime) * 1000 > ($timeout ?? $this->timeout)) {
                    throw new MessageDispatchTimeoutException($timeout);
                }
            }
        }
        return null;
    }

}
