<?php

namespace MessageBus\Adapter\Queue;

use MessageBus\Adapter\Queue\QueueAdapter;
use Predis\Client;

class RedisAdapter implements QueueAdapter
{
    private Client $client;
    private string $queueKey;

    public function __construct(string $host, int $port, string $queueKey)
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port,
        ]);
        $this->queueKey = $queueKey;
    }

    public function enqueue(string $serializedMessage): void
    {
        $this->client->lpush($this->queueKey, [$serializedMessage]);
    }

    public function dequeue(): ?string
    {
        return $this->client->rpop($this->queueKey);
    }
}