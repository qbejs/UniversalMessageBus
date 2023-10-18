<?php

namespace MessageBus;

class MessageValidator
{
    public function validate($message): array
    {
        if (!$message instanceof ValidatableMessageInterface) {
            return [];
        }

        $schema = $message->getValidationSchema();
        $errors = [];

        foreach ($schema as $field => $rules) {
            if ($rules === 'required' && empty($message->$field)) {
                $errors[$field] = "Pole $field jest wymagane.";
            }
        }

        return $errors;
    }
}
