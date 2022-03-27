<?php

namespace App\Services;

use App\Http\BaseResponse;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\UserTrait;
use App\Traits\WalletTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserService
{
    use UserTrait;
    use WalletTrait;

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
        DB::beginTransaction();

        $user = $this->createUser($data);

        if(!$user instanceof User)
        {
            DB::rollBack();
            return $this->baseResponse->jsonResponse(false,"Create user has failed. Please try again later.",[],500);
        }

        $wallet = $this->addWalletToUser($user);

        if(!$wallet instanceof Wallet)
        {
            DB::rollBack();
            return $this->baseResponse->jsonResponse(false,"Create user's wallet has failed. Please try again later.",[],500);
        }

        DB::commit();

        $user = $this->convertUserObjectToArray($user);

        $wallet = $this->convertWalletObjectToArray($wallet);

        $response = [
            'user' => $user,
            'wallet' => $wallet
        ];

        return $this->baseResponse->jsonResponse(true,"User created successfully",$response,201);
    }

    /**
     * @param User $user
     * @param array $data
     * @return JsonResponse
     */
    public function userUpdate(User $user,array $data): JsonResponse
    {
        DB::beginTransaction();

        $user = $this->updateUser($user,$data);

        if(!$user instanceof User)
        {
            DB::rollBack();
            return $this->baseResponse->jsonResponse(false,"Update user has failed. Please try again later.",[],400);
        }

        DB::commit();

        $user = $this->convertUserObjectToArray($user);

        return $this->baseResponse->jsonResponse(true,"User updated successfully",$user,200);
    }

    /**
     * @param array $credentials
     * @return User|null
     */
    public function userLogin(array $credentials): ?User
    {
        return $this->loginWithEmailAndPassword($credentials);
    }
}
