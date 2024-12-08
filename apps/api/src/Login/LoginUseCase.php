<?php

namespace App\Login;

use App\Repository\User\IUserRepository;
use App\Entity\User;
use App\Enum\UserRole;

class LoginUseCase
{
    private IUserRepository $repository;
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $email, string $password): array
    {
        $user = $this->repository->findByEmail($email);
        if ($user === null) {
            return ["status" => false, "error" => "User not found"];
        }
        if (!password_verify($password, $user->getPassword())) {
            return ["status" => false, "error" => "Invalid password"];
        }
        return ["status" => true, "user" => $user];
    }
}