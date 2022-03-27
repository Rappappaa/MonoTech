<?php

namespace App\Traits;

use App\Models\AssignPromotionCode;
use App\Models\PromotionCode;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
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
     * @return string|null
     */
    public function assignCodeToUser(User $user,string $promotionCode): ?string
    {
        $promotionCode = PromotionCode::Where('code','=',$promotionCode)->first();

        if($promotionCode === null)
        {
            return 'Promotion code could not found.';
        }

        if($promotionCode->quota < 1)
        {
            return 'This promotion code has been reached maximum user.';
        }

        if($promotionCode->start_date > Carbon::now())
        {
            return 'This promotion code will start on ' . $promotionCode->start_date;
        }

        if($promotionCode->end_date < Carbon::now())
        {
            return 'This promotion code has been expired.';
        }

        $ifUsed = AssignPromotionCode::Where('user_id','=',$user->id)->Where('promotion_code_id','=',$promotionCode->id)->first();

        if($ifUsed !== null)
        {
            return 'You have been used this code already.';
        }

        $user->wallet->balance += $promotionCode->amount;

        $user->wallet->save();

        --$promotionCode->quota;

        $promotionCode->save();

        AssignPromotionCode::create([
            'user_id' => $user->id,
            'promotion_code_id' => $promotionCode->id
        ]);

        return '';
    }

    /**
     * @return PromotionCode[]|Collection
     */
    public function findAll(): Collection|array
    {
        $promotionCodes = PromotionCode::orderBy('id', 'ASC')->get()->toArray();

        $response = [];

        foreach ($promotionCodes as $item)
        {

            $tempUser = [];

            $checkAssign = AssignPromotionCode::Where('promotion_code_id','=',$item['id'])->get()->toArray();

            if($checkAssign !== [])
            {
                foreach($checkAssign as $assign)
                {
                    $user = User::Where('id','=',$assign['user_id'])->first();

                    if($user !== [])
                    {
                        $wallet = Wallet::Where('user_id','=',$user->id)->first();

                        if($wallet !== null)
                        {
                            $tempUser[] = [
                                'id' => $user->id,
                                'username' => $user->username,
                                'firstname' => $user->firstname,
                                'lastname' => $user->lastname,
                                'email' => $user->email,
                                'wallet' => [
                                    'id' => $wallet->id,
                                    'balance' => $wallet->balance,
                                    'updated_at' => $wallet->updated_at
                                ]
                            ];
                        }
                    }
                }

                $response[] = [
                    'id' => $item['id'],
                    'code' => $item['code'],
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'],
                    'amount' => $item['amount'],
                    'quota' => $item['quota'],
                    'users' => $tempUser
                ];
            }else{
                $response[] = [
                    'id' => $item['id'],
                    'code' => $item['code'],
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'],
                    'amount' => $item['amount'],
                    'quota' => $item['quota'],
                    'users' => []
                ];
            }
        }

        return $response;
    }

    /**
     * @param string $id
     * @return array
     */
    public function findById(string $id): array
    {
        $promotionCode = PromotionCode::find($id);

        $response = [];

        $checkAssign = AssignPromotionCode::Where('promotion_code_id','=',$promotionCode->id)->get()->toArray();

        if($checkAssign !== [])
        {
            foreach($checkAssign as $assign)
            {
                $users = User::Where('id','=',$assign['user_id'])->get()->toArray();
                if($users !== [])
                {
                    foreach($users as $user)
                    {
                        $wallet = Wallet::Where('user_id','=',$user['id'])->first();
                        if($wallet !== null)
                        {
                            $response[] = [
                                'id' => $promotionCode->id,
                                'code' => $promotionCode->code,
                                'start_date' => $promotionCode->start_date,
                                'end_date' => $promotionCode->end_date,
                                'amount' => $promotionCode->amount,
                                'quota' => $promotionCode->quota,
                                'users' => [
                                    'id' => $user['id'],
                                    'username' => $user['username'],
                                    'firstname' => $user['firstname'],
                                    'lastname' => $user['lastname'],
                                    'email' => $user['email'],
                                    'wallet' => [
                                        'id' => $wallet->id,
                                        'balance' => $wallet->balance,
                                        'updated_at' => $wallet->updated_at
                                    ]
                                ]
                            ];
                        }
                    }
                }
            }
        }else{
            $response[] = [
                'id' => $promotionCode->id,
                'code' => $promotionCode->code,
                'start_date' => $promotionCode->start_date,
                'end_date' => $promotionCode->end_date,
                'amount' => $promotionCode->amount,
                'quota' => $promotionCode->quota,
                'users' => []
            ];
        }

        return $response;
    }
}
