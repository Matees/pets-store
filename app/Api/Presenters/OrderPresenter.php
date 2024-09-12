<?php

namespace App\Api\Presenters;

use App\Api\Models\Order;
use App\Api\Repositories\OrderRepository;
use App\Api\Traits\RequestMethodTrait;
use App\Api\Traits\ResponseTrait;
use App\Api\Validators\OrderValidator;
use Nette\Application\UI\Presenter;
use SimpleXMLElement;

class OrderPresenter extends Presenter
{

    use RequestMethodTrait, ResponseTrait;

    const XML_FILE_NAME = '/orders.xml';

    const ROUTES = [
        'create' => 'POST',
        'inventory' => 'GET',
        'detail' => 'GET',
        'delete' => 'DELETE',
    ];

    public function startup(): void
    {
        parent::startup();

        $this->checkRequestMethod($this, self::ROUTES[$this->getParameter('action')]);
    }


    public function __construct(private readonly OrderRepository $orderRepository) {
        parent::__construct();

        $xmlDir = __DIR__.'/../../data';
        if (!is_dir($xmlDir)) {
            mkdir($xmlDir, 0777, true);
        }

        if (!file_exists($xmlDir . self::XML_FILE_NAME)) {
            $xml = new SimpleXMLElement('<orders/>');
            $xml->asXML($xmlDir . self::XML_FILE_NAME);
        }
    }

    public function actionCreate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        $addedOrder = null;
        try {
            OrderValidator::validateRequiredFields($data);

            $order = Order::createFromJson($data);

            $addedOrder = $this->orderRepository->addOrderToXml($order);
        }catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $addedOrder->toArray());
    }

    public function actionDetail($id)
    {
        $pet = null;
        try {
            $pet = $this->orderRepository->findById($id);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $pet->toArray());
    }

    public function actionDelete($id)
    {
        try {
            $this->orderRepository->deleteOrderFromXml($id);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, 'Order successfully deleted from XML');
    }

    public function actionInventory()
    {
        $inventory = null;
        try {
            $inventory = $this->orderRepository->getInventory();
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $inventory);
    }
}
