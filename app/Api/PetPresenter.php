<?php

namespace App\Api;

use Nette\Application\UI\Presenter;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\Request;

class PetPresenter extends Presenter
{
    private $pets = [
        // Example initial pet data
        ['id' => 1, 'name' => 'cat', 'category' => ['id' => 1, 'name' => 'Cats'], 'photoUrls' => ['cat.jpg'], 'tags' => [['id' => 1, 'name' => 'cute']], 'status' => 'available'],
    ];

    public function __construct() {
        parent::__construct();
    }

    public function actionCreate()
    {
        // Return the created pet information
        $this->sendJson(['status' => 'Pet created', 'pet' => []], 201);
    }
}
