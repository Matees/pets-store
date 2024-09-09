<?php

namespace App\Api\Presenters;

use App\Api\Models\Pet;
use App\Api\Repositories\PetRepository;
use App\Api\Traits\RequestMethodTrait;
use App\Api\Validators\PetValidator;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\Http\FileUpload;
use SimpleXMLElement;
use Tracy\Debugger;

class PetPresenter extends Presenter
{
    use RequestMethodTrait;

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

        Debugger::log($xmlDir, Debugger::INFO);
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

        PetValidator::validateRequiredFields($data);

        $pet = Pet::createFromJson($data);

        $this->petRepository->addPetToXml($pet);

        $this->sendJson(['success' => 'Pet successfully saved']);
    }

    public function actionUpdate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        PetValidator::validateRequiredFields($data);

        $pet = Pet::createFromJson($data);

        $this->petRepository->updatePetInXml($pet);

        $this->sendJson(['success' => 'Pet successfully updated to XML']);
    }

    public function actionDetail($id)
    {
        $pet = $this->petRepository->findById($id);

        $this->sendJson(['data' => $pet]);
    }

    public function actionFindByStatus()
    {
        $status = $this->getParameter('status');

        PetValidator::validateStatus($status);

        $pets = $this->petRepository->findByStatus($status);

        $this->sendJson(['data' => $pets]);
    }

    public function actionFindByTags()
    {
        $tags = $this->getParameter('tags');

        PetValidator::validateTags($tags);

        $pets = $this->petRepository->findByTags($tags);

        $this->sendJson(['data' => $pets]);
    }

    public function actionUpdateWithParameters()
    {
        $parameters = $this->getParameters();

        PetValidator::validateRequiredFields($parameters, ['name', 'status']);

        $this->petRepository->updatePetWithParametersInXml($parameters);

        $this->sendJson(['data' => 'Pet successfully updated to XML']);
    }

    public function actionUploadImage($id)
    {
        $file = $this->getHttpRequest()->getFile('image');

        if (!$file instanceof FileUpload || !$file->isOk()) {
            $this->sendResponse(new JsonResponse(['error' => 'No file uploaded or file is not valid.'], 400));
        }

        // Validate file type and size
        if ($file->getContentType() !== 'image/jpeg' && $file->getContentType() !== 'image/png') {
            $this->sendResponse(new JsonResponse(['error' => 'Invalid file type. Only JPEG and PNG are allowed.'], 400));
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5 MB limit
            $this->sendResponse(new JsonResponse(['error' => 'File size exceeds the limit of 5 MB.'], 400));
        }

        // Save the file
        $uploadDir = __DIR__ . '/../../../www/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $this->petRepository->addImageUrl($id, $this->getHttpRequest()->getUrl()->getBaseUrl() . 'uploads/' . $file->getSanitizedName());
        $file->move($uploadDir . $file->getSanitizedName());

        // Send a success response
        $this->sendResponse(new JsonResponse(['message' => 'File uploaded successfully.']));
    }

    public function actionDelete($id)
    {
        $this->petRepository->deletePetFromXml($id);

        $this->sendJson(['data' => 'Pet successfully deleted from XML']);
    }
}
