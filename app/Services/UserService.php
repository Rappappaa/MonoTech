<?php

namespace App\Services;

use App\Http\BaseResponse;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\JsonResponse;

class UserService
{
    use UserTrait;

    private BaseResponse $baseResponse;

    public function __construct(BaseResponse $baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function userRegister(array $data): JsonResponse
    {
        $user = $this->createUser($data);

        if(!$user instanceof User)
        {
            return $this->baseResponse->jsonResponse(false,"Create user has failed. Please try again later.",[],500);
        }

        $user = $this->convertObjectToArray($user);

        return $this->baseResponse->jsonResponse(true,"User created successfully",$user,201);
    }

    public function userUpdate(array $data): JsonResponse
    {
        $user = $this->updateUser($data);

        if(!$user instanceof User)
        {
            return $this->baseResponse->jsonResponse(false,"Update user has failed. Please try again later.",[],400);
        }

        $user = $this->convertObjectToArray($user);

        return $this->baseResponse->jsonResponse(true,"User updated successfully",$user,200);
    }
}
