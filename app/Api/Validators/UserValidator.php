<?php

namespace App\Api\Validators;

use InvalidArgumentException;

class UserValidator
{

    const REQUIRED_FIELDS = ['id', 'username', 'firstName', 'lastName', 'email', 'password', 'phone', 'userStatus'];

    /**
     * Validate the presence of required fields in the pet data and validate them.
     *
     * @param array $data
     * @param array $requiredFields
     */
    public static function validateRequiredFields(array $data, array $requiredFields = self::REQUIRED_FIELDS): void
    {

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException(sprintf('Missing required field: %s', $field));
            }

            match ($field) {
                'id', 'userStatus' => is_int($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an integer."),
                'username', 'firstName', 'lastName', 'email', 'password', 'phone' => is_string($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an string."),
                default => null
            };
        }
    }
}


