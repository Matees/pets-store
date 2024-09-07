<?php

namespace App\Api\Validators;

use App\Api\Enums\Status;
use InvalidArgumentException;

class PetValidator
{

    const REQUIRED_FIELDS = ['id', 'name', 'category', 'photoUrls', 'tags', 'status'];

    /**
     * Validate the given pet data array.
     *
     * @param array $data
     * @throws InvalidArgumentException if validation fails.
     */
    public static function validate(array $data): void
    {
        // Validate required fields
        self::validateRequiredFields($data);

        // Validate id (must be an integer)
        if (!is_int($data['id'])) {
            throw new InvalidArgumentException('Invalid value for "id". It must be an integer.');
        }

        // Validate name (must be a string)
        if (!is_string($data['name'])) {
            throw new InvalidArgumentException('Invalid value for "name". It must be a string.');
        }

        // Validate category (must have id and name)
        if (!isset($data['category']['id'], $data['category']['name'])) {
            throw new InvalidArgumentException('Invalid category data. Both "id" and "name" are required.');
        }

        if (!is_int($data['category']['id'])) {
            throw new InvalidArgumentException('Invalid value for "category.id". It must be an integer.');
        }

        if (!is_string($data['category']['name'])) {
            throw new InvalidArgumentException('Invalid value for "category.name". It must be a string.');
        }

        // Validate photoUrls (must be an array of strings)
        if (!is_array($data['photoUrls']) || array_filter($data['photoUrls'], 'is_string') !== $data['photoUrls']) {
            throw new InvalidArgumentException('Invalid value for "photoUrls". It must be an array of strings.');
        }

        // Validate tags (must be an array of tag objects with id and name)
        if (!is_array($data['tags'])) {
            throw new InvalidArgumentException('Invalid value for "tags". It must be an array of tag objects.');
        }

        foreach ($data['tags'] as $tag) {
            if (!isset($tag['id'], $tag['name'])) {
                throw new InvalidArgumentException('Each tag must have "id" and "name".');
            }

            if (!is_int($tag['id'])) {
                throw new InvalidArgumentException('Invalid value for "tag.id". It must be an integer.');
            }

            if (!is_string($tag['name'])) {
                throw new InvalidArgumentException('Invalid value for "tag.name". It must be a string.');
            }
        }

        if (!is_string($data['status']) || !Status::isValid($data['status'])) {
            throw new InvalidArgumentException('Invalid value for "status". It must be one of: "available", "pending", "sold".');
        }
    }

    /**
     * Validate the presence of required fields in the pet data.
     *
     * @param array $data
     * @throws InvalidArgumentException if required fields are missing.
     */
    private static function validateRequiredFields(array $data): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException(sprintf('Missing required field: %s', $field));
            }
        }
    }
}


