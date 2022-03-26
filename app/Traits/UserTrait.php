<?php

namespace App\Traits;

use App\Models\User;

trait UserTrait
{
    /**
     * @param array $data
     * @return User|null
     */
    public function createUser(array $data): ?User
    {
        try{
            return User::create([
                'username' => $data['username'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        }catch (\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @param array $data
     * @return ?User
     */
    public function updateUser(array $data): ?User
    {
        $user = User::find((int)$data['id']);

        if($user === null)
        {
            return null;
        }

        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];

        if($user->save())
        {
            return $user;
        }

        return null;
    }

    /**
     * @param User $user
     * @return array
     */
    public function convertUserObjectToArray(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }
}
