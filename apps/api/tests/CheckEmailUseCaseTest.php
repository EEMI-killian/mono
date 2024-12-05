<?php

namespace App\Tests;

use App\CheckEmail\CheckEmailUseCase;
use App\Entity\User;
use App\Repository\User\IUserRepository;
use PHPUnit\Framework\TestCase;


class CheckEmailUseCaseTest extends TestCase
{
    public function testCheckEmailUseCase()
    {
        /** @var IUserRepository&\PHPUnit\Framework\MockObject\MockObject $userRepository */
        $userRepository = $this->createMock(IUserRepository::class);
        $data = ['email' => 'AlreadyTaken@email.com'];
        $userRepository->method('findByEmail')->willReturn(
            new User(
                'John',
                'Doe',
                'email@email.com',
                'securepassword123',
                '1234567890',
                '123 Main St',
                'Anytown',
                'Anystate',
                '12345',
                'USA',
                '2000-01-01',
                false,
                false
            ));
        $checkEmailUseCase = new CheckEmailUseCase($userRepository);
        $response = $checkEmailUseCase->execute($data);
        $this->assertEquals('{"status":false,"error":"User already exists"}', $response);
    }
    public function testCheckEmailUseCaseValidEmail()
    {
        /** @var IUserRepository&\PHPUnit\Framework\MockObject\MockObject $userRepository */
        $userRepository = $this->createMock(IUserRepository::class);
        $data = ['email' => 'email@email.com'];
        $userRepository->method('findByEmail')->willReturn(null);
        $checkEmailUseCase = new CheckEmailUseCase($userRepository);
        $response = $checkEmailUseCase->execute($data);
        $this->assertEquals('{"status":true}', $response);
    }
}