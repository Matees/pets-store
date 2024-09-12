<?php
namespace App\Api\Models;

use SimpleXMLElement;

class User
{
    public int $id;
    public string $username;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $password;
    public string $phone;
    public int $userStatus;

    public function __construct(int $id, string $username, string $firstName, string $lastName, string $email, string $password, string $phone, int $userStatus)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->userStatus = $userStatus;
    }


    public static function createFromJson(array $data): User
    {
        return new User(
            $data['id'],
            $data['username'],
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password'],
            $data['phone'],
            $data['userStatus'],
        );
    }

    public static function createFromXml(SimpleXMLElement $xml): User
    {
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return new User(
            $data['id'],
            is_array($data['username']) ? '' : $data['username'],
            is_array($data['firstName']) ? '' : $data['firstName'],
            is_array($data['lastName']) ? '' : $data['lastName'],
            is_array($data['email']) ? '' : $data['email'],
            is_array($data['password']) ? '' : $data['password'],
            is_array($data['phone']) ? '' : $data['phone'],
            is_array($data['userStatus']) ? '' : $data['userStatus'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,  // You might want to exclude this in some cases for security reasons
            'phone' => $this->phone,
            'userStatus' => $this->userStatus,
        ];
    }
}
