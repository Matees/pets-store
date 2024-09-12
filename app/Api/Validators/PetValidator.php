<?php

namespace App\Api\Validators;

use App\Api\Enums\PetStatus;
use InvalidArgumentException;
use Nette\Http\FileUpload;

class PetValidator
{

    const REQUIRED_FIELDS = ['id', 'name', 'category', 'photoUrls', 'tags', 'status'];

    public static function validateStatus($status): void
    {
        if (!is_string($status) || !PetStatus::isValid($status)) {
            $statuses = PetStatus::getStatuses();
            throw new InvalidArgumentException("Invalid value for status. It must be one of: $statuses.");
        }
    }

    public static function validateTags(array $tags, $update = false): void
    {
        if (!$update && empty($tags)) {
            throw new InvalidArgumentException('Tags must not be empty.');
        }

        foreach ($tags as $tag) {
            if (!isset($tag['id'], $tag['name'])) {
                throw new InvalidArgumentException('Each tag must have "id" and "name".');
            }

            if (!is_int($tag['id'])) {
                throw new InvalidArgumentException('Invalid value for "tag.id". It must be an integer.');
            }

            if (!is_string($tag['name']) || (!$update && empty($tag['name']))) {
                throw new InvalidArgumentException('Invalid value for "tag.name". It must be a string.');
            }
        }
    }

    public static function validateCategory(array $category, $update): void
    {
        if (!isset($category['name'])) {
            throw new InvalidArgumentException('Invalid category data. "name" is required.');
        }

        if (!is_string($category['name']) || (!$update && empty($category['name']))) {
            throw new InvalidArgumentException('Invalid value for "category.name". It must be a string.');
        }
    }

    public static function validateImage($file): void
    {
        if (!$file instanceof FileUpload || !$file->isOk()) {
            throw new InvalidArgumentException('No file uploaded or file is not valid.');
        }

        if ($file->getContentType() !== 'image/jpeg' && $file->getContentType() !== 'image/png') {
            throw new InvalidArgumentException('Invalid file type. Only JPEG and PNG are allowed.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5 MB limit
            throw new InvalidArgumentException('File size exceeds the limit of 5 MB.');
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
                'id' => is_int($data[$field]) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an integer."),
                'name' => ($update && is_string($data[$field])) || (!$update && is_string($data[$field]) && !empty($data[$field])) ? true : throw new InvalidArgumentException("Invalid value for $field. It must be an string."),
                'category' => self::validateCategory($data[$field], $update),
                'photoUrls' => !$update ? (!empty($data[$field]) ? (array_filter($data[$field], 'is_string') === $data[$field] ? true : throw new InvalidArgumentException('Invalid value for "photoUrls". It must be an array of strings.')) : throw new InvalidArgumentException('Invalid value for "photoUrls". It must be an array of strings.')) : (array_filter($data[$field], 'is_string') === $data[$field] ? true : throw new InvalidArgumentException('Invalid value for "photoUrls". It must be an array of strings.')),
                'tags' => self::validateTags($data[$field], $update),
                'status' => self::validateStatus($data[$field]),
                default => null
            };
        }
    }
}


