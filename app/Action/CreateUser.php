<?php

namespace App\Action;

use App\Models\User;

readonly class CreateUser
{
    public function handle(array $data): User
    {
        return User::query()->create([
            'name' => $data['ProfileName'],
            'phone' => "+" . $data['WaId'],
        ]);
    }
}
