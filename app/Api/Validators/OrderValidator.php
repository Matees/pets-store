<?php

namespace App\Api\Validators;

use App\Api\Enums\OrderStatus;
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
    public static function validateRequiredFields(array $data, array $requiredFields = self::REQUIRED_FIELDS, $update = false): void
    {
        if ($update) {
            $inputPropertiesNames = array_keys($data);
            if ($invalidProperties = array_diff($inputPropertiesNames, $requiredFields)) {
                throw new InvalidArgumentException(sprintf('Invalid attribute: %s', implode(', ', $invalidProperties)));
            }
        }

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) && !$update) {
                throw new InvalidArgumentException(sprintf('Missing required field: %s', $field));
            }

            match ($field) {
                'id', 'petId' => is_int($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an integer."),
                'quantity' => is_int($data[$field]) || (!$update && !empty($data[$field])) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an integer."),
                'shipDate' => ($update && is_string($data[$field])) || (!$update && is_string($data[$field]) && !empty($data[$field])) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be datetime."),
                'status' => self::validateStatus($data[$field]),
                'complete' => is_bool($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an bool."),
                default => null
            };
        }
    }
}


