<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\Model\User;
use App\Models\User as EloquentUser;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function execute(array $data): User
    {
        $eloquentUser = EloquentUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return new User(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->email
        );
    }
}
