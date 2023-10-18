<?php

namespace MessageBus\Adapter\Queue;


use MessageBus\Adapter\Queue\QueueAdapter;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqAdapter implements QueueAdapter
{
    private AMQPStreamConnection $connection;
    private AbstractChannel|AMQPChannel $channel;
    private string $queueName;

    /**
     * @throws \Exception
     */
    public function __construct(string $host, int $port, string $user, string $password, string $queueName)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
        $this->queueName = $queueName;
        $this->channel->queue_declare($queueName, false, true, false, false);
    }

    public function enqueue(string $serializedMessage): void
    {
        $msg = new AMQPMessage($serializedMessage);
        $this->channel->basic_publish($msg, '', $this->queueName);
    }

    public function dequeue(): ?string
    {
        $message = $this->channel->basic_get($this->queueName);
        if ($message) {
            $this->channel->basic_ack($message->get('delivery_tag'));
            return $message->body;
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}