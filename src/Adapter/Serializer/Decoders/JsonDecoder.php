<?php

namespace MessageBus\Adapter\Serializer\Decoders;

use MessageBus\Adapter\Serializer\DecoderInterface;

class JsonDecoder implements DecoderInterface
{
    public function decode(string $data, string $messageClass)
    {
        $arrayData = json_decode($data, true);
        return new $messageClass($arrayData);
    }
}
