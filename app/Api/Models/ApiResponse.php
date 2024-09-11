<?php

namespace App\Api\Models;

class ApiResponse
{
    public int $code;
    public string $type;
    public string|array $message;

    public function __construct(int $code, string $type, string|array $message)
    {
        $this->code = $code;
        $this->type = $type;
        $this->message = $message;
    }
}