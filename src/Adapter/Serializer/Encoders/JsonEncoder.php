<?php

namespace MessageBus\Adapter\Serializer\Encoders;

use MessageBus\Adapter\Serializer\EncoderInterface;

class JsonEncoder implements EncoderInterface
{
    public function encode($message): string
    {
        return json_encode($message->toArray());
    }
}
