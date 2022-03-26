<?php

namespace App\Traits;

use App\Models\PromotionCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait PromotionCodeTrait
{
    /**
     * @param array $data
     * @return PromotionCode|null
     */
    public function registerPromotionCode(array $data): ?PromotionCode
    {
        try{
            return PromotionCode::create([
                'code' => strtoupper(Str::random(12)),
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'amount' => $data['amount'],
                'quota' => $data['quota']
            ]);
        }catch (\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @param User $user
     * @param string $promotionCode
     * @return bool
     */
    public function assaignPromotionCode(User $user,string $promotionCode): bool
    {
        $promotionCode = PromotionCode::Where('code','=',$promotionCode)->first();

        if($promotionCode === null)
        {
            return false;
        }

        $user->balance = $promotionCode->amount;

        $user->save();

        --$promotionCode->quota;

        $promotionCode->save();

        return true;
    }

    /**
     * @return PromotionCode[]|Collection
     */
    public function findAll(): Collection|array
    {
        return PromotionCode::all()->toArray();
    }

    /**
     * @param string $id
     * @return PromotionCode|null
     */
    public function findById(string $id): ?PromotionCode
    {
        return PromotionCode::find($id);
    }
}
