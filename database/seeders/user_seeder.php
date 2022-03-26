<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 10; $i++)
        {
            $faker = Faker\Factory::create();

            $timestamp = Carbon::now();

            DB::table('user')->insert([
                'username' => $faker->userName,
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => $faker->email,
                'password' => $faker->password,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

    }
}
