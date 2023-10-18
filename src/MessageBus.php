<?php

namespace MessageBus;

use MessageBus\Adapter\Queue\QueueAdapter;
use MessageBus\Configuration\ConfigurationManager;
use MessageBus\Event\AfterDispatchEvent;
use MessageBus\Exception\InvalidMessageFormat;
use MessageBus\Exception\NoActiveConfigurationException;
use MessageBus\Middleware\MiddlewareManager;
use MessageBus\Event\BeforeDispatchEvent;

class MessageBus
{
    private MessageDispatcher $messageDispatcher;
    private MessageValidator $validator;
    private MessageSerializer $serializer;
    private EventDispatcher $eventDispatcher;
    private MiddlewareManager $middlewareManager;
    private QueueAdapter $queueAdapter;
    private string $environment;
    private ConfigurationManager $configManager;

    public function __construct(
        MessageDispatcher    $messageDispatcher,
        MessageValidator     $validator,
        MessageSerializer    $serializer,
        EventDispatcher      $eventDispatcher,
        MiddlewareManager    $middlewareManager,
        QueueAdapter         $queueAdapter,
        ConfigurationManager $configManager,
        string               $environment = 'production'
    ) {
        $this->messageDispatcher = $messageDispatcher;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->middlewareManager = $middlewareManager;
        $this->queueAdapter = $queueAdapter;
        $this->environment = $environment;
        $this->configManager = $configManager;
        $this->configManager->loadConfiguration($this->environment);
    }

    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }

    public function getConfigurationValue(string $key, $default = null)
    {
        $config = $this->configManager->getActiveConfig();
        if (!$config) {
            throw new NoActiveConfigurationException();
        }

        return $config->get($key, $default);
    }

    /**
     * @throws \Exception
     */
    public function dispatch(array $message, int $timeout = null): ?array
    {
        // Before dispatch event
        $this->eventDispatcher->dispatch(new BeforeDispatchEvent($message));

        // Validation
        if (!$this->validator->validate($message)) {
            throw new InvalidMessageFormat();
        }

        // Middleware processing
        $message = $this->middlewareManager->process($message);

        // Serialization
        $serializedMessage = $this->serializer->serialize($message);

        $this->messageDispatcher->setAdapter($this->queueAdapter);

        // Dispatch with retry
        $response = $this->messageDispatcher->withRetry($serializedMessage, $this->getConfigurationValue('timeout', $timeout));

        // After dispatch event
        $this->eventDispatcher->dispatch(new AfterDispatchEvent($message, $response));

        return $response;
    }
}
