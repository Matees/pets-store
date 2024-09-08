<?php
namespace App\Api\Models;

use App\Api\Enums\PetStatus;
use App\Api\Validators\PetValidator;
use SimpleXMLElement;
use Tracy\Debugger;

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

        return self::createFromJson($data);
    }
}
