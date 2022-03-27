<?php

namespace App\Http\Controllers;


use App\Http\BaseResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private UserService $userService;
    private BaseResponse $baseResponse;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService,BaseResponse $baseResponse)
    {
        $this->userService = $userService;
        $this->baseResponse = $baseResponse;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = json_decode($request->getContent(),true);

        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $validator = Validator::make($credentials, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        $user = $this->userService->loginWithEmailAndPassword($credentials);

        if($user === null)
        {
            return $this->baseResponse->jsonResponse(false,"User login failed. Please check your credentials.",[],400);
        }

        if (! $token = auth('api')->login($user)) {
            return $this->baseResponse->jsonResponse(false,"Token could not created. Please try again later.",[],401);
        }

        return $this->baseResponse->jsonResponse(true,"Welcome " . $user->firstname,['token' => $token],200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->invalidate();

        return $this->baseResponse->jsonResponse(true,"Logout success",[],200);
    }
}
