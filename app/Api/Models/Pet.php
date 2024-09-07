<?php
namespace App\Api\Models;

use App\Api\Enums\Status;
use App\Api\Validators\PetValidator;

class Pet
{
    public int $id;
    public string $name;
    public Category $category;
    public array $photoUrls;
    public array $tags;
    public Status $status;

    public function __construct(int $id, string $name, Category $category, array $photoUrls, array $tags, Status $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->photoUrls = $photoUrls;
        $this->tags = $tags;
        $this->status = $status;
    }

    public static function createFromJson(array $data): Pet
    {
        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        // Validate JSON data
        PetValidator::validate($data);

        // Convert status to PetStatus enum
        $status = Status::from($data['status']);

        // Create Pet model if valid
        $category = new Category($data['category']['id'], $data['category']['name']);
        $tags = array_map(function ($tagData) {
            return new Tag($tagData['id'], $tagData['name']);
        }, $data['tags']);

        return new Pet(
            $data['id'],
            $data['name'],
            $category,
            $data['photoUrls'],
            $tags,
            $status
        );
    }

    public static function createFromXml(string $xmlData): Pet
    {
        // Parse the XML string
        $xml = simplexml_load_string($xmlData);

        if ($xml === false) {
            throw new \InvalidArgumentException('Invalid XML format.');
        }

        // Convert SimpleXMLElement to array
        $json = json_encode($xml);
        $data = json_decode($json, true);

        PetValidator::validate($data);

        // Validate the XML structure
        if (!isset($data['id'], $data['name'], $data['category'], $data['photoUrls'], $data['tags'], $data['status'])) {
            throw new \InvalidArgumentException('Invalid XML data: required fields are missing.');
        }

        // Create Category model
        $category = new Category($data['category']['id'], $data['category']['name']);

        // Create array of Tag models
        $tags = array_map(function ($tagData) {
            return new Tag($tagData['id'], $tagData['name']);
        }, $data['tags']);

        // Create and return Pet model
        return new Pet(
            $data['id'],
            $data['name'],
            $category,
            $data['photoUrls'],
            $tags,
            $data['status']
        );
    }

    public function overwriteFrom(Pet $pet): void
    {
        $this->name = $pet->name;
        $this->category = $pet->category;
        $this->photoUrls = $pet->photoUrls;
        $this->tags = $pet->tags;
        $this->status = $pet->status;
    }

}
