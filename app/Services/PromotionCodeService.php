<?php

namespace App\Services;

use App\Http\BaseResponse;
use App\Models\AssignPromotionCode;
use App\Models\PromotionCode;
use App\Models\User;
use App\Traits\PromotionCodeTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PromotionCodeService
{
    use PromotionCodeTrait;

    private BaseResponse $baseResponse;

    public function __construct(BaseResponse $baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    /**
     * @return JsonResponse
     */
    public function getPromotionCodes(): JsonResponse
    {
        $promotionCodes = $this->findAll();

        return $this->baseResponse->jsonResponse(true,count($promotionCodes) .' promotion code exist.',$promotionCodes,200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getPromotionCodesById(string $id): JsonResponse
    {
        $promotionCode = $this->findById($id);

        if($promotionCode === [])
        {
            return $this->baseResponse->jsonResponse(true,'Promotion code could not found.',[],200);
        }

        return $this->baseResponse->jsonResponse(true,'Promotion code found.',$promotionCode,200);
    }

    public function createPromotionCode(array $data): JsonResponse
    {
        DB::beginTransaction();

        $promotionCode = $this->registerPromotionCode($data);

        if(!$promotionCode instanceof PromotionCode)
        {
            DB::rollBack();
            return $this->baseResponse->jsonResponse(false,"Create promotion code has failed. Please try again later.",[],500);
        }

        DB::commit();

        return $this->baseResponse->jsonResponse(true,"Promotion Code created successfully",$promotionCode->toArray(),201);
    }

    /**
     * @param User $user
     * @param array $data
     * @return JsonResponse
     */
    public function assignPromotionCode(User $user,array $data): JsonResponse
    {
        DB::beginTransaction();

        $assignCode = $this->assignCodeToUser($user,$data['code']);

        if($assignCode !== '')
        {
            DB::rollBack();
            return $this->baseResponse->jsonResponse(false,$assignCode,[],400);
        }

        DB::commit();

        return $this->baseResponse->jsonResponse(true,"Promotion Code assigned successfully",[],201);
    }

}
