<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class promotion_code_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 30; $i++)
        {
            DB::table('promotion_code')->insert([
                'code' => Str::random(12),
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(random_int(1,60)),
                'amount' => random_int(0,500),
                'quota' => random_int(0,50),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
