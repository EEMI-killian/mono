<?php 
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\SignUp\SignUpUseCase;
use App\Repository\User\IUserRepository;
use App\Entity\User;

class SignUpUseCaseTest extends TestCase
{
    public function testHappyPath(): void
    {
        /** @var IUserRepository&\PHPUnit\Framework\MockObject\MockObject $repository */
        $repository = $this->createMock(IUserRepository::class);
        $repository->expects($this->once())
            ->method('findByEmail')
            ->willReturn(null);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        $signUpUseCase = new SignUpUseCase($repository);

        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'securepassword123',
            'phoneNumber' => '1234567890',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'Anystate',
            'zipCode' => '12345',
            'country' => 'USA',
            'birthDate' => '2000-01-01',
        ];

        $response = $signUpUseCase->execute($data);

        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertTrue($responseData['status']);
        $this->assertEquals('User created successfully', $responseData['message']);
    }
}