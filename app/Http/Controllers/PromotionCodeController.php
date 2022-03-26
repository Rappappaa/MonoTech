<?php

namespace App\Http\Controllers;

use App\Http\BaseResponse;
use App\Services\PromotionCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionCodeController extends Controller
{
    private BaseResponse $baseResponse;
    private PromotionCodeService $promotionCodeService;

    public function __construct(BaseResponse $baseResponse,PromotionCodeService $promotionCodeService)
    {
        $this->baseResponse = $baseResponse;
        $this->promotionCodeService = $promotionCodeService;
    }

    public function getPromotionCodes(): JsonResponse
    {
        return $this->promotionCodeService->getPromotionCodes();
    }

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

    public function promotionCodeRegister(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

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
}
