<?php

namespace MessageBus\Adapter\Serializer\Encoders;

use MessageBus\Adapter\Serializer\EncoderInterface;

class XmlEncoder implements EncoderInterface
{
    public function encode($message): string
    {
        $xml = new \SimpleXMLElement('<message/>');
        array_walk_recursive($message, function ($value, $key) use ($xml) {
            $xml->addChild($key, $value);
        });
        return $xml->asXML();
    }
}
