<?php

namespace MessageBus\Adapter\Serializer;

interface EncoderInterface
{
    public function encode($message): string;
}
