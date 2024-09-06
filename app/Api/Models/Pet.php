<?php
namespace App\Api\Models;

class Pet
{
    public int $id;
    public string $name;
    public Category $category;
    public array $photoUrls;
    public array $tags;
    public string $status;

    public function __construct(int $id, string $name, Category $category, array $photoUrls, array $tags, string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->photoUrls = $photoUrls;
        $this->tags = $tags;
        $this->status = $status;
    }
}
