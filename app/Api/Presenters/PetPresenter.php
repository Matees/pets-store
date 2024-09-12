<?php

namespace App\Api\Presenters;

use App\Api\Models\Pet;
use App\Api\Repositories\PetRepository;
use App\Api\Traits\RequestMethodTrait;
use App\Api\Traits\ResponseTrait;
use App\Api\Validators\PetValidator;
use InvalidArgumentException;
use Nette\Application\UI\Presenter;
use SimpleXMLElement;

class PetPresenter extends Presenter
{
    use RequestMethodTrait, ResponseTrait;

    CONST XML_FILE_NAME = '/pets.xml';
    const ROUTES = [
        'findByTags' => 'GET',
        'findByStatus' => 'GET',
        'create' => 'POST',
        'update' => 'PUT',
        'uploadImage' => 'POST',
        'detail' => 'GET',
        'updateWithParameters' => 'POST',
        'delete' => 'DELETE',
    ];

    public function startup(): void
    {
        parent::startup();

        $this->checkRequestMethod($this, self::ROUTES[$this->getParameter('action')]);
    }


    public function __construct(private readonly PetRepository $petRepository) {
        parent::__construct();

        $xmlDir = __DIR__.'/../../data';
        if (!is_dir($xmlDir)) {
            mkdir($xmlDir, 0777, true);
        }

        if (!file_exists($xmlDir . self::XML_FILE_NAME)) {
            $xml = new SimpleXMLElement('<pets/>');
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

        $createdPet = null;
        try {
            PetValidator::validateRequiredFields($data);

            $pet = Pet::createFromJson($data);

            $createdPet = $this->petRepository->addPetToXml($pet);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, $createdPet->toArray());
    }

    public function actionUpdate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        $updatedPet = null;
        try {
            PetValidator::validateRequiredFields($data, update: true);

            $pet = Pet::createFromJson($data);

            $updatedPet = $this->petRepository->updatePetInXml($pet);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, $updatedPet->toArray());
    }

    public function actionDetail($id)
    {
        $pet = null;
        try {
            $pet = $this->petRepository->findById($id);
        }catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }
        $this->sendSuccess(200, $pet->toArray());
    }

    public function actionFindByStatus()
    {
        $status = $this->getParameter('status');

        $pets = [];
        try {
            PetValidator::validateStatus($status);

            $pets = $this->petRepository->findByStatus($status);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, json_encode(array_map(fn (Pet $pet) =>  $pet->toArray(), $pets)));
    }

    public function actionFindByTags()
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $queryParams = explode('&', $queryString);

        $tags = [];
        foreach ($queryParams as $param) {
            if (str_starts_with($param, 'tags=')) {
                $tagValue = urldecode(substr($param, strlen('tags=')));

                $tags[] = $tagValue;
            }
        }

        $pets = [];
        try {
            if (empty($tags)) {
                throw new InvalidArgumentException('Tags must not be empty');
            }

            $pets = $this->petRepository->findByTags($tags);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, json_encode(array_map(fn (Pet $pet) =>  $pet->toArray(), $pets)));
    }

    public function actionUpdateWithParameters()
    {
        $parameters = $this->getParameters();

        try {
            PetValidator::validateRequiredFields($parameters, ['name', 'status']);

            $updatedPet = $this->petRepository->updatePetWithParametersInXml($parameters);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, $updatedPet->toArray());
    }

    public function actionUploadImage($id)
    {
        $file = $this->getHttpRequest()->getFile('image');

        try {
            PetValidator::validateImage($file);

            // Save the file
            $uploadDir = __DIR__ . '/../../../www/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $updatedPet = $this->petRepository->addImageUrl($id, $this->getHttpRequest()->getUrl()->getBaseUrl() . 'uploads/' . $file->getSanitizedName());
            $file->move($uploadDir . $file->getSanitizedName());
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        // Send a success response
        $this->sendSuccess(200, $updatedPet->toArray());
    }

    public function actionDelete($id)
    {
        try{
            $this->petRepository->deletePetFromXml($id);
        } catch (\Exception $exception) {
            $this->sendError($exception->getCode(), $exception->getMessage());
        }

        $this->sendSuccess(200, 'Pet successfully deleted from XML');
    }
}
