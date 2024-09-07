<?php

namespace App\Api\Presenters\Pet;

use App\Api\Models\Pet;
use App\Api\Repositories\PetRepository;
use App\Api\Validators\PetValidator;
use Nette\Application\UI\Presenter;

class PetPresenter extends Presenter
{

    public function __construct(private readonly PetRepository $petRepository) {
        parent::__construct();
    }

    public function actionCreate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Validate JSON data
        PetValidator::validate($data);

        // Create Pet from JSON
        $pet = Pet::createFromJson($data);

        // Save the pet data to XML
        $this->petRepository->save($pet);

        $this->sendJson(['success' => 'Pet saved to XML']);
    }

    public function actionUpdate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Validate JSON data
        PetValidator::validate($data);

        // Create Pet from JSON
        $pet = Pet::createFromJson($data);

        // Save the pet data to XML
        $this->petRepository->update($pet);

        $this->sendJson(['success' => 'Pet saved to XML']);
    }
}
