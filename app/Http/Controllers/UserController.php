<?php

namespace App\Http\Controllers;

use App\Http\BaseResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController
{
    private BaseResponse $baseResponse;
    private UserService $userService;

    public function __construct(BaseResponse $baseResponse,UserService $userService)
    {
        $this->baseResponse = $baseResponse;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function userRegister(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        if($data === null)
        {
            return $this->baseResponse->jsonResponse(false,"Empty request body.",[],204);
        }

        $rules = [
            'username' => 'required|string|max:50|unique:user,username',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:50',
            'email' => 'required|email|unique:user,email',
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        return $this->userService->userRegister($data);
    }

    public function userUpdate(Request $request): JsonResponse
    {
        $token = $request->header()['authorization'][0];

        $split = explode(' ',$token);

        $token = $split[1];

        $user = JWTAuth::toUser($token);

        $data = json_decode($request->getContent(),true);

        if($data === null)
        {
            return $this->baseResponse->jsonResponse(false,"Empty request body.",[],204);
        }

        $rules = [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        return $this->userService->userUpdate($user,$data);
    }
}
