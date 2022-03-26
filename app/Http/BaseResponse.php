<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;

class BaseResponse
{

    public function jsonResponse(bool $status, string $message, array $data, int $responseCode): JsonResponse
    {
        return new JsonResponse(['status' => $status, 'message' => $message, 'data' => $data], $responseCode);
    }
}
