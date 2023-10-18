<?php

namespace MessageBus\Exception;

class InvalidMessageFormat extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid message format.");
    }
}
