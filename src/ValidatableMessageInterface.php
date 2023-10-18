<?php

namespace MessageBus;

interface ValidatableMessageInterface
{
    public function getValidationSchema(): array;
}
