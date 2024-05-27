<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function authenticate($username, $password)
    {
        $users = $this->userModel
            ->findAllBy('username', $username);

        if (empty($users)) {
            return ['user' => null, 'error' => 'User not found'];
        }

        $user = $users[0];

        if (password_verify($password, $user['password'])) {
            return ['user' => $user, 'error' => null];
        }

        return ['user' => null, 'error' => 'Invalid password'];
    }
}
