<?php

namespace App\Api\Repositories;

use App\Api\Models\Pet;
use DOMDocument;
use DOMXPath;
use InvalidArgumentException;
use RuntimeException;

class PetRepository
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function findById(int $id, $exists = false): ?Pet
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        foreach ($xml->pet as $petNode) {
            if ((int) $petNode->id == $id) {
                // Convert XML node to Pet object
                return Pet::createFromXml($petNode);
            }
        }

        if ($exists) {
            return null;
        }

        throw new InvalidArgumentException("Invalid Pet ID.", 401);
    }

    public function findByStatus(string $status): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        // Load XML file
        $xml = simplexml_load_file($this->filePath);

        $pets = [];
        foreach ($xml->pet as $petNode) {
            if ((string)$petNode->status === $status) {
                // Convert XML node to Pet object
                $pets[] = Pet::createFromXml($petNode);
            }
        }

        return $pets;
    }

    public function findByTags(array $tags): array
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $pets = [];
        foreach ($xml->pet as $petNode) {
            foreach ($petNode->tags as $tag) {
                if (in_array((string)$tag->tag->name, $tags)) {
                    $pets[] = Pet::createFromXml($petNode);

                    continue 2;
                }
            }
        }

        return $pets;
    }

    public function saveXML($xml): bool
    {
        return $xml->asXML($this->filePath);
    }

    public function addPetToXml(Pet $pet): Pet
    {
        $existingPet = $this->findById($pet->id, true);

        if ($existingPet) {
            throw new RuntimeException('Pet already exists.');
        }

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        // Create the <pet> element as a child of <pets>
        $petElement = $xml->addChild('pet');

        $petElement->addChild('id', $pet->id);
        $petElement->addChild('name', htmlspecialchars($pet->name));

        $category = $petElement->addChild('category');
        $category->addChild('id', $pet->category->id);
        $category->addChild('name', htmlspecialchars($pet->category->name));

        $photoUrls = $petElement->addChild('photoUrls');
        foreach ($pet->photoUrls as $url) {
            $photoUrls->addChild('photoUrl', htmlspecialchars($url));
        }

        $tags = $petElement->addChild('tags');
        foreach ($pet->tags as $tag) {
            $tagElement = $tags->addChild('tag');
            $tagElement->addChild('id', $tag->id);
            $tagElement->addChild('name', htmlspecialchars($tag->name));
        }

        $petElement->addChild('status', htmlspecialchars($pet->status->value));

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return Pet::createFromXml($petElement);
    }

    public function updatePetInXml(Pet $pet): Pet
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $foundPetNode = null;
        foreach ($xml->pet as $petNode) {
            if ((int) $petNode->id == $pet->id) {
                $petNode->name = $pet->name;
                $petNode->status = $pet->status->value;

                $petNode->category->id = $pet->category->id;
                $petNode->category->name = $pet->category->name;

                unset($petNode->photoUrls);
                unset($petNode->tags);

                $photoUrls = $petNode->addChild('photoUrls');
                foreach ($pet->photoUrls as $url) {
                    $photoUrls->addChild('photoUrl', htmlspecialchars($url));
                }

                $tags = $petNode->addChild('tags');
                foreach ($pet->tags as $tag) {
                    $tagElement = $tags->addChild('tag');
                    $tagElement->addChild('id', $tag->id);
                    $tagElement->addChild('name', htmlspecialchars($tag->name));
                }

                $foundPetNode = $petNode;
                break;
            }
        }

        if (!$foundPetNode) {
            throw new InvalidArgumentException("Invalid Pet ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return Pet::createFromXml($foundPetNode);
    }

    public function updatePetWithParametersInXml($parameters): Pet
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $foundPetNode = null;
        foreach ($xml->pet as $petNode) {
            if ((int) $petNode->id == $parameters['id']) {
                $petNode->name = $parameters['name'];
                $petNode->status = $parameters['status'];

                $foundPetNode = $petNode;
                break;
            }
        }

        if (!$foundPetNode) {
            throw new InvalidArgumentException("Invalid Pet ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return Pet::createFromXml($foundPetNode);
    }

    public function deletePetFromXml($id): void
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $nodeDeleted = false;
        foreach ($xml->pet as $petNode) {
            if ((int) $petNode->id == $id) {
                $dom = new DOMDocument();
                $dom->loadXML($xml->asXML());
                $xpath = new DOMXPath($dom);

                $nodes = $xpath->query("//pet[id='$id']");

                foreach ($nodes as $node) {
                    $node->parentNode->removeChild($node);
                }

                $xml = simplexml_load_string($dom->saveXML());

                $nodeDeleted = true;
                break;
            }
        }

        if (!$nodeDeleted) {
            throw new InvalidArgumentException("Invalid Pet ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };
    }

    public function addImageUrl($id, $url): Pet
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $foundPetNode = null;
        foreach ($xml->pet as $petNode) {
            if ((int) $petNode->id == $id) {
                $petNode->photoUrls->addChild('photoUrl', htmlspecialchars($url));

                $foundPetNode = $petNode;
                break;
            }
        }

        if (!$foundPetNode) {
            throw new InvalidArgumentException("Invalid Pet ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return Pet::createFromXml($foundPetNode);
    }
}

