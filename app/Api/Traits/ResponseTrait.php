<?php

namespace App\Api\Traits;

use App\Api\Models\ApiResponse;

trait ResponseTrait
{
    public function sendSuccess(int $code, string|array $message): void
    {
        $this->sendJson(new ApiResponse($code, 'success', $message));
    }

    public function sendError(int $code, string $message): void
    {
        $this->sendJson(new ApiResponse($code, 'error', $message));
    }
}

