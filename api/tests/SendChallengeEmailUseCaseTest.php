<?php 

namespace App\SendChallengeEmail;

use PHPUnit\Framework\TestCase;
use App\Repository\Challenge\IChallengeRepository;

class SendChallengeEmailUseCaseTest extends TestCase
{
    public function testSendChallengeEmailUseCase()
    {
        
        /** @var IChallengeRepository&\PHPUnit\Framework\MockObject\MockObject $challengeRepository */
        $challengeRepository = $this->createMock(IChallengeRepository::class);
        $sendChallengeEmailUseCase = new SendChallengeEmailUseCase($challengeRepository);
        $challengeRepository->expects($this->once())
            ->method('setChallenge');

        
        $response = $sendChallengeEmailUseCase->execute(['email' => 'email@email.com'],false);
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertTrue($responseData['status']);        
    }
}