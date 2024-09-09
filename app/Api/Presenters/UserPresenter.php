<?php

namespace App\Api\Presenters;

use App\Api\Models\User;
use App\Api\Repositories\UserRepository;
use App\Api\Services\AuthenticationService;
use App\Api\Traits\RequestMethodTrait;
use App\Api\Validators\UserValidator;
use Nette\Application\UI\Presenter;
use SimpleXMLElement;

class UserPresenter extends Presenter
{
    use RequestMethodTrait;

    const XML_FILE_NAME = '/users.xml';
    const ROUTES = [
        'login' => 'GET',
        'logout' => 'GET',
        'createWithList' => 'POST',
        'detail' => 'GET',
        'delete' => 'DELETE',
        'update' => 'PUT',
    ];

    public function startup(): void
    {
        parent::startup();

        $this->checkRequestMethod($this, self::ROUTES[$this->getParameter('action')]);
    }

    public function __construct(private readonly UserRepository $userRepository) {
        parent::__construct();

        $xmlDir = __DIR__.'/../../data';
        if (!is_dir($xmlDir)) {
            mkdir($xmlDir, 0777, true);
        }

        if (!file_exists($xmlDir . self::XML_FILE_NAME)) {
            $xml = new SimpleXMLElement('<users/>');
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

        UserValidator::validateRequiredFields($data);

        $user = User::createFromJson($data);

        $addedUser = $this->userRepository->addUserToXml($user);

        $this->sendJson(['success' => 'User successfully saved', 'data' => $addedUser]);
    }

    public function actionDetail($name)
    {
        if (!AuthenticationService::isLoggedIn()) {
            $this->error('Not logged in.', 401);
        }

        $user = $this->userRepository->findByName($name);

        $this->sendJson(['data' => $user]);
    }

    public function actionDelete($name)
    {
        $this->userRepository->deleteUserFromXml($name);

        $this->sendJson(['data' => 'User successfully deleted from XML']);
    }

    public function actionCreateWithList()
    {
        $requestBody = file_get_contents('php://input');
        $usersInput = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        $users = [];
        foreach ($usersInput as $user) {
            UserValidator::validateRequiredFields($user);

            $userModel = User::createFromJson($user);

            $users[] = $this->userRepository->addUserToXml($userModel);
        }

        $this->sendJson(['data' => $users]);
    }

    public function actionUpdate($name)
    {
        if (!$this->getRequest()->isMethod('PUT')) {
            $this->error('Invalid request method. Only POST is allowed.', 405);
        }

        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        UserValidator::validateRequiredFields($data);

        $user = User::createFromJson($data);

        $addedUser = $this->userRepository->updateUserInXml($name, $user);

        $this->sendJson(['success' => 'User successfully updated', 'data' => $addedUser]);
    }

    public function actionLogin()
    {
        $username = $this->getParameter('username');
        $password = $this->getParameter('password');

        $logged = (new AuthenticationService())->login($username, $password, $this->userRepository->getValidUsers());

        $this->sendJson(['data' => $logged]);
    }

    public function actionLogout()
    {
        (new AuthenticationService())->logout();

        $this->sendJson(['success' => 'Logged out']);
    }
}
