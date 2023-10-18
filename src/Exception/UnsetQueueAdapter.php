<?php

namespace MessageBus\Exception;

class UnsetQueueAdapter extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("No queue adapter set.");
    }
}
