<?php 
namespace App\Repository\User;

use App\Entity\User;

interface IUserRepository
{
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
}