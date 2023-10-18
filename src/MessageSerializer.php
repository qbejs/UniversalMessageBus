<?php

namespace MessageBus;

use MessageBus\Adapter\Serializer\DecoderInterface;
use MessageBus\Adapter\Serializer\EncoderInterface;

class MessageSerializer
{
    private EncoderInterface $encoder;
    private DecoderInterface $decoder;

    public function __construct(EncoderInterface $encoder, DecoderInterface $decoder)
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    public function serialize($message): string
    {
        return $this->encoder->encode($message);
    }

    public function deserialize(string $data, string $messageClass)
    {
        return $this->decoder->decode($data, $messageClass);
    }
}
