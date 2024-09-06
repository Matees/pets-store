<?php

namespace App\Api\Repositories;

use App\Api\Models\Pet;
use SimpleXMLElement;

class PetRepository
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function save(Pet $pet): void
    {
        $xml = $this->petToXml($pet);
        $xml->asXML($this->filePath);
    }

    private function petToXml(Pet $pet): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<pet/>');

        $xml->addChild('id', $pet->id);
        $xml->addChild('name', htmlspecialchars($pet->name));

        $category = $xml->addChild('category');
        $category->addChild('id', $pet->category->id);
        $category->addChild('name', htmlspecialchars($pet->category->name));

        $photoUrls = $xml->addChild('photoUrls');
        foreach ($pet->photoUrls as $url) {
            $photoUrls->addChild('url', htmlspecialchars($url));
        }

        $tags = $xml->addChild('tags');
        foreach ($pet->tags as $tag) {
            $tagElement = $tags->addChild('tag');
            $tagElement->addChild('id', $tag->id);
            $tagElement->addChild('name', htmlspecialchars($tag->name));
        }

        $xml->addChild('status', htmlspecialchars($pet->status));

        return $xml;
    }
}

