<?php

namespace App\Api\Presenters;

use App\Api\Models\User;
use App\Api\Repositories\UserRepository;
use App\Api\Services\AuthenticationService;
use App\Api\Traits\RequestMethodTrait;
use App\Api\Traits\ResponseTrait;
use App\Api\Validators\UserValidator;
use Nette\Application\UI\Presenter;
use SimpleXMLElement;

class UserPresenter extends Presenter
{
    use RequestMethodTrait, ResponseTrait;

    const XML_FILE_NAME = '/users.xml';
    const ROUTES = [
        'login' => 'GET',
        'logout' => 'GET',
        'createWithList' => 'POST',
        'detail' => 'GET',
        'delete' => 'DELETE',
        'update' => 'PUT',
        'create' => 'Post',
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

        try {
            UserValidator::validateRequiredFields($data);

            $user = User::createFromJson($data);

            $addedUser = $this->userRepository->addUserToXml($user);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $addedUser->toArray());
    }

    public function actionDetail($name)
    {
        if (!AuthenticationService::isLoggedIn()) {
            $this->sendError(401 , 'Not logged in.');
        }

        try {
            $user = $this->userRepository->findByName($name);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $user->toArray());
    }

    public function actionDelete($name)
    {
        if (!AuthenticationService::isLoggedIn()) {
            $this->sendError(401, 'Not logged in.');
        }

        try {
            $this->userRepository->deleteUserFromXml($name);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200,' User successfully deleted from XML');
    }

    public function actionCreateWithList()
    {
        if (!AuthenticationService::isLoggedIn()) {
            $this->sendError(401 , 'Not logged in.');
        }

        $requestBody = file_get_contents('php://input');
        $usersInput = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        $users = [];
        try {
            foreach ($usersInput as $user) {
                UserValidator::validateRequiredFields($user);

                $userModel = User::createFromJson($user);

                $users[] = $this->userRepository->addUserToXml($userModel);
            }
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $users);
    }

    public function actionUpdate($name)
    {
        if (!AuthenticationService::isLoggedIn()) {
            $this->sendError(401 , 'Not logged in.');
        }

        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        try {
            UserValidator::validateRequiredFields($data, update: true);

            $user = User::createFromJson($data);

            $addedUser = $this->userRepository->updateUserInXml($name, $user);
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $addedUser->toArray());
    }

    public function actionLogin()
    {
        $username = $this->getParameter('username');
        $password = $this->getParameter('password');

        $logged = false;
        try {
            $logged = (new AuthenticationService())->login($username, $password, $this->userRepository->getValidUsers());
        } catch (\Exception $e) {
            $this->sendError($e->getCode(), $e->getMessage());
        }

        $this->sendSuccess(200, $logged ? 'Successfully logged in' : 'Login failed');
    }

    public function actionLogout()
    {
        (new AuthenticationService())->logout();

        $this->sendJson(['success' => 'Logged out']);
    }
}
