<?php

namespace MessageBus\Adapter\Serializer;

interface DecoderInterface
{
    public function decode(string $data, string $messageClass);
}
