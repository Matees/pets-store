<?php

namespace App\Api\Repositories;

use App\Api\Models\Order;
use DOMDocument;
use DOMXPath;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;

class OrderRepository
{
    private string $filePath;
    private PetRepository $petRepository;

    public function __construct(string $filePath, PetRepository $petRepository)
    {
        $this->filePath = $filePath;
        $this->petRepository = $petRepository;
    }

    public function saveXML($xml): bool
    {
        return $xml->asXML($this->filePath);
    }

    public function addOrderToXml(Order $order): Order
    {
        $pet = $this->petRepository->findById($order->petId);

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        if (!empty($xml->order)) {
            $existingOrder = $this->findById($order->id, true);

            if ($existingOrder) {
                throw new RuntimeException('Order already exists.');
            }
        }

        // Create the <pet> element as a child of <pets>
        $orderElement = $xml->addChild('order');

        $orderElement->addChild('id', $order->id);
        $orderElement->addChild('petId', htmlspecialchars($pet->id));
        $orderElement->addChild('quantity', htmlspecialchars($order->quantity));
        $orderElement->addChild('shipDate', htmlspecialchars($order->shipDate));
        $orderElement->addChild('status', htmlspecialchars($order->status->value));
        $orderElement->addChild('complete', htmlspecialchars($order->complete));

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return Order::createFromXml($orderElement);
    }

    public function findById(int $id, $exists = false): ?Order
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);


        foreach ($xml->order as $orderNode) {
            if ((int) $orderNode->id == $id) {
                // Convert XML node to Pet object
                return Order::createFromXml($orderNode);
            }
        }

        if ($exists) {
            return null;
        }

        throw new InvalidArgumentException("Invalid Order ID.");
    }

    public function deleteOrderFromXml($id): void
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $nodeDeleted = false;
        foreach ($xml->order as $orderNode) {
            if ((int) $orderNode->id == $id) {
                $dom = new DOMDocument();
                $dom->loadXML($xml->asXML());
                $xpath = new DOMXPath($dom);

                $nodes = $xpath->query("//order[id='$id']");

                foreach ($nodes as $node) {
                    $node->parentNode->removeChild($node);
                }

                $xml = simplexml_load_string($dom->saveXML());

                $nodeDeleted = true;
                break;
            }
        }

        if (!$nodeDeleted) {
            throw new InvalidArgumentException("Invalid Order ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        }
    }

    public function getInventory(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $quantitiesByStatus = [];

        foreach ($xml->order as $orderNode) {
            $status = (string) $orderNode->status;
            $quantity= (int) $orderNode->quantity;

            if (!isset($quantitiesByStatus[$status])) {
                $quantitiesByStatus[$status] = 0;
            }
            $quantitiesByStatus[$status] += $quantity;
        }

        return $quantitiesByStatus;
    }
}

