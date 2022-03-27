<?php

namespace App\Http\Controllers;

use App\Http\BaseResponse;
use App\Models\User;
use App\Services\PromotionCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PromotionCodeController extends Controller
{
    private BaseResponse $baseResponse;
    private PromotionCodeService $promotionCodeService;

    public function __construct(BaseResponse $baseResponse,PromotionCodeService $promotionCodeService)
    {
        $this->baseResponse = $baseResponse;
        $this->promotionCodeService = $promotionCodeService;
    }

    /**
     * @return JsonResponse
     */
    public function getPromotionCodes(): JsonResponse
    {
        return $this->promotionCodeService->getPromotionCodes();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getPromotionCodeById($id): JsonResponse
    {
        $data = ['id' => $id];

        $rules = [
            'id' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        return $this->promotionCodeService->getPromotionCodesById($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function promotionCodeRegister(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        if($data === null)
        {
            return $this->baseResponse->jsonResponse(false,"Empty request body.",[],204);
        }

        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'amount' => 'required|integer',
            'quota' => 'required|integer'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        return $this->promotionCodeService->createPromotionCode($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function assignPromotionCode(Request $request): JsonResponse
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
            'code' => 'required|string|min:12|max:12'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return $this->baseResponse->jsonResponse(false,"Validation has failed. Please check your fields",$validator->getMessageBag()->all(),400);
        }

        return $this->promotionCodeService->assignPromotionCode($user,$data);
    }
}
