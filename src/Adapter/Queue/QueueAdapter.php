<?php

namespace MessageBus\Adapter\Queue;

interface QueueAdapter
{
    public function enqueue(string $serializedMessage): void;
    public function dequeue(): ?string;
}