<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Wallet;
use PhpParser\Node\Expr\Cast\Double;

trait WalletTrait
{
    /**
     * @param User $user
     * @return Wallet|null
     */
    public function addWalletToUser(User $user): ?Wallet
    {
        try{
            return Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }catch (\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @param Wallet $wallet
     * @param Double $amount
     * @return Wallet|null
     */
    public function addPromotionCodeToWallet(Wallet $wallet,double $amount): ?Wallet
    {
        try{
            $wallet->amount += $amount;

            $wallet->save();

            return $wallet;
        }catch (\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @param Wallet $wallet
     * @return array
     */
    public function convertWalletObjectToArray(Wallet $wallet): array
    {
        return [
            'id' => $wallet->id,
            'balance' => $wallet->balance,
            'created_at' => $wallet->created_at,
            'updated_at' => $wallet->updated_at
        ];
    }
}
