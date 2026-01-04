<?php

namespace App\Actions;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class RegisterUserAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $username, string $phonenumber): User
    {
        try {
            // Upsert Logic
            $user = $this->userRepository->findByUsername($username);

            if ($user) {
                if ($user->phonenumber !== $phonenumber) {
                    $this->userRepository->update($user, ['phonenumber' => $phonenumber]);
                }

                return $user;
            }

            return $this->userRepository->create([
                'username' => $username,
                'phonenumber' => $phonenumber,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to register user', 500);
        }
    }
}
