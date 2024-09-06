<?php

namespace App\Api\Presenters\Pet;

use App\Api\Models\Category;
use App\Api\Models\Pet;
use App\Api\Models\Tag;
use App\Api\Repositories\PetRepository;
use Nette\Application\UI\Presenter;

class PetPresenter extends Presenter
{

    public function __construct(private PetRepository $repository) {
        parent::__construct();
    }

    public function actionCreate()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendJson(['error' => 'Invalid JSON']);
        }

        // Create the Pet model from the request data
        $category = new Category($data['category']['id'], $data['category']['name']);
        $tags = array_map(fn($tag) => new Tag($tag['id'], $tag['name']), $data['tags']);
        $pet = new Pet($data['id'], $data['name'], $category, $data['photoUrls'], $tags, $data['status']);

        // Save the pet data to XML
        $this->repository->save($pet);

        $this->sendJson(['success' => 'Pet data saved to XML']);
    }
}
