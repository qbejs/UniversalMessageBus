<?php

namespace MessageBus\Event;

abstract class Event
{
    private array $message;
    private ?array $response;

    public function __construct(array $message, ?array $response = null)
    {

        $this->message = $message;
        $this->response = $response;
    }

    /**
     * Get the dispatched message.
     *
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * Get the response after dispatching the message.
     *
     * @return ?array
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }
}
