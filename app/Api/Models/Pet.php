<?php
namespace App\Api\Models;

use App\Api\Enums\PetStatus;
use SimpleXMLElement;

class Pet
{
    public int $id;
    public string $name;
    public Category $category;
    public array $photoUrls;
    public array $tags;
    public PetStatus $status;

    public function __construct(int $id, string $name, Category $category, array $photoUrls, array $tags, PetStatus $status)
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
        $status = PetStatus::from($data['status']);

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

    public static function createFromXml(SimpleXMLElement $xml): Pet
    {
        $json = json_encode($xml);
        $data = json_decode($json, true);

        $status = PetStatus::from($data['status']);

        $category = new Category(is_array($data['category']['id']) ? null  : $data['category']['id'], is_array($data['category']['name']) ? '' : $data['category']['name']);
        $tags = array_map(function ($tagData) {
            return new Tag($tagData['id'] ?? null, $tagData['name'] ?? '');
        }, $data['tags']);

        return new Pet(
            $data['id'],
            is_array($data['name']) ? '' : $data['name'],
            $category,
            empty($data['photoUrls']) ? [] : (!isset($data['photoUrls']['photoUrl']) ? [$data['photoUrls']] : (is_string($data['photoUrls']['photoUrl']) ? [$data['photoUrls']['photoUrl']] : $data['photoUrls']['photoUrl'])),
            $tags,
            $status
        );
    }

    public function toString(): string
    {
    $tags = array_map(fn($tag) => $tag->name, $this->tags);


    return sprintf(
        "Pet ID: %d, Name: %s, Category: %s, PhotoUrls: %s, Tags: %s, Status: %s",
        $this->id,
        $this->name,
        $this->category->name,
        implode(',', $this->photoUrls),
        implode(',', $tags),
        $this->status->value
    );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'photoUrls' => $this->photoUrls,
            'tags' => array_map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ], $this->tags),
            'status' => $this->status->value,
        ];
    }
}
