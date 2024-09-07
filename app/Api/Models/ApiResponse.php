<?php

namespace App\Api\Models;

use Nette\Application\Responses\JsonResponse;

class ApiResponse
{
    public int $code;
    public string $type;
    public string $message;

    public function __construct(int $code, string $type, string $message)
    {
        $this->code = $code;
        $this->type = $type;
        $this->message = $message;
    }
}