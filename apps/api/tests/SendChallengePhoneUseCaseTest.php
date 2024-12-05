<?php 

namespace App\SendChallengePhone;

use PHPUnit\Framework\TestCase;
use App\Repository\Challenge\IChallengeRepository;

class SendChallengePhoneUseCaseTest extends TestCase
{
    public function testSendChallengePhoneUseCase(): void 
    {
        
        /** @var IChallengeRepository&\PHPUnit\Framework\MockObject\MockObject $challengeRepository */
        $challengeRepository = $this->createMock(IChallengeRepository::class);
        $sendChallengePhoneUseCase = new SendChallengePhoneUseCase($challengeRepository);
        $challengeRepository->expects($this->once())
            ->method('setChallenge');

        
        $response = $sendChallengePhoneUseCase->execute(['email' => 'email@email.com',],false);
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertTrue($responseData['status']);        
    }
}