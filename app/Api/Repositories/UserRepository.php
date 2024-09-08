<?php

namespace App\Api\Repositories;

use App\Api\Models\User;
use DOMDocument;
use DOMXPath;
use InvalidArgumentException;
use RuntimeException;
use Tracy\Debugger;

class UserRepository
{
    private string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function saveXML($xml): bool
    {
        return $xml->asXML($this->filePath);
    }

    public function addUserToXml(User $user): User
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        if (!empty($xml->user)) {
            $existingUser = $this->findById($user->id, true);

            if ($existingUser) {
                throw new RuntimeException('User already exists.');
            }

            $existingName = $this->findByName($user->username, true);

            if ($existingName) {
                throw new RuntimeException('Username already exists.');
            }

            $existingUser = $this->findByEmail($user->email, true);

            if ($existingUser) {
                throw new RuntimeException('Email already exists.');
            }
        }

        $userElement = $xml->addChild('user');

        $userElement->addChild('id', $user->id);
        $userElement->addChild('username', htmlspecialchars($user->username));
        $userElement->addChild('firstName', htmlspecialchars($user->firstName));
        $userElement->addChild('lastName', htmlspecialchars($user->lastName));
        $userElement->addChild('email', htmlspecialchars($user->email));
        $userElement->addChild('password', htmlspecialchars($user->password));
        $userElement->addChild('phone', htmlspecialchars($user->phone));
        $userElement->addChild('userStatus', htmlspecialchars($user->userStatus));

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return User::createFromXml($userElement);
    }

    public function findById(int $id, $exists = false): ?User
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);


        foreach ($xml->user as $userNode) {
            if ((int) $userNode->id == $id) {
                // Convert XML node to Pet object
                return User::createFromXml($userNode);
            }
        }

        if ($exists) {
            return null;
        }

        throw new InvalidArgumentException("Invalid User ID.");
    }

    public function findByName(string $name, $exists = false): ?User
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);


        foreach ($xml->user as $userNode) {
            if ((string) $userNode->username == $name) {
                // Convert XML node to Pet object
                return User::createFromXml($userNode);
            }
        }

        if ($exists) {
            return null;
        }

        throw new InvalidArgumentException("Invalid User Name.");
    }

    public function findByEmail(string $email, $exists = false): ?User
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);


        foreach ($xml->user as $userNode) {
            if ((string) $userNode->email == $email) {
                // Convert XML node to Pet object
                return User::createFromXml($userNode);
            }
        }

        if ($exists) {
            return null;
        }

        throw new InvalidArgumentException("Invalid User Email.");
    }

    public function deleteUserFromXml($name): void
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        Debugger::log($name, 'info');
        $nodeDeleted = false;
        foreach ($xml->user as $userNode) {
            if ((string) $userNode->username == $name) {
                $dom = new DOMDocument();
                $dom->loadXML($xml->asXML());
                $xpath = new DOMXPath($dom);

                $nodes = $xpath->query("//user[username='$name']");

                foreach ($nodes as $node) {
                    $node->parentNode->removeChild($node);
                }

                $xml = simplexml_load_string($dom->saveXML());

                $nodeDeleted = true;
                break;
            }
        }

        if (!$nodeDeleted) {
            throw new InvalidArgumentException("Invalid User ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        }
    }

    public function updateUserInXml($name, User $user): User
    {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $foundUserNode = null;
        foreach ($xml->user as $userNode) {
            if ((string) $userNode->username == $name) {
                $userNode->id = $user->id;
                $userNode->username = $user->username;
                $userNode->firstName = $user->firstName;
                $userNode->lastName = $user->lastName;
                $userNode->email = $user->email;
                $userNode->password = $user->password;
                $userNode->phone = $user->phone;
                $userNode->userStatus = $user->userStatus;

                $foundUserNode = $userNode;
                break;
            }
        }

        if (!$foundUserNode) {
            throw new InvalidArgumentException("Invalid User ID.");
        }

        if (!$this->saveXml($xml)){
            throw new RuntimeException("XML wasn't saved.");
        };

        return User::createFromXml($foundUserNode);
    }

    public function getValidUsers(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);

        $users = [];

        foreach ($xml->user as $userNode) {
            $users[] = User::createFromXml($userNode);
        }

        return $users;
    }
}

