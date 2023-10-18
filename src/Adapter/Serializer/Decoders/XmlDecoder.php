<?php

namespace MessageBus\Adapter\Serializer\Decoders;

use MessageBus\Adapter\Serializer\DecoderInterface;

class XmlDecoder implements DecoderInterface
{
    public function decode(string $data, string $messageClass)
    {
        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}
