<?php

namespace App\Api\Validators;

use App\Api\Enums\OrderStatus;
use App\Api\Enums\PetStatus;
use InvalidArgumentException;

class OrderValidator
{

    const REQUIRED_FIELDS = ['id', 'petId', 'quantity', 'shipDate', 'status', 'complete'];

    public static function validateStatus($status): void
    {
        if (!is_string($status) || !OrderStatus::isValid($status)) {
            $statuses = OrderStatus::getStatuses();
            throw new InvalidArgumentException("Invalid value for status. It must be one of: $statuses.");
        }
    }

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
                'id', 'petId', 'quantity' => is_int($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an integer."),
                'shipData' => is_string($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an string."),
                'status' => self::validateStatus($data[$field]),
                'complete' => is_bool($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an bool."),
                default => null
            };
        }
    }
}


