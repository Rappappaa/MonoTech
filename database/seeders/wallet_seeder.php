<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class wallet_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 10; $i++)
        {
            $timestamp = Carbon::now();

            DB::table('wallet')->insert([
                'user_id' => $i,
                'balance' => random_int(0,500),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
