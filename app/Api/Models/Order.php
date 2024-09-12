<?php
namespace App\Api\Models;

use App\Api\Enums\OrderStatus;
use SimpleXMLElement;

class Order
{
    public int $id;
    public int $petId;
    public int $quantity;
    public string $shipDate;
    public OrderStatus $status;
    public bool $complete;

    public function __construct(int $id, int $petId, int $quantity, string $shipDate, OrderStatus $status, bool $complete)
    {
        $this->id = $id;
        $this->petId = $petId;
        $this->quantity = $quantity;
        $this->shipDate = $shipDate;
        $this->status = $status;
        $this->complete = $complete;
    }

    public static function createFromJson(array $data): Order
    {
        $status = OrderStatus::from($data['status']);

        return new Order(
            $data['id'],
            $data['petId'],
            $data['quantity'],
            $data['shipDate'],
            $status,
            $data['complete']
        );
    }

    public static function createFromXml(SimpleXMLElement $xml): Order
    {
        $json = json_encode($xml);
        $data = json_decode($json, true);

        $status = OrderStatus::from($data['status']);


        return new Order(
            $data['id'],
            $data['petId'],
            is_array($data['quantity']) ? '' : $data['quantity'],
            is_array($data['shipDate']) ? '' : $data['shipDate'],
            $status,
            is_array($data['complete']) ? false : $data['complete'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'petId' => $this->petId,
            'quantity' => $this->quantity,
            'shipDate' => $this->shipDate,
            'status' => $this->status->value,
            'complete' => $this->complete
        ];
    }
}
