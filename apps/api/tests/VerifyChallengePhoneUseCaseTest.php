<?php

namespace App\VerifyChallengePhone;

use PHPUnit\Framework\TestCase;
use App\Repository\Challenge\IChallengeRepository;
use App\Repository\User\IUserRepository;
use App\Entity\Challenge;





class VerifyChallengePhoneUseCaseTest extends TestCase {

    public function testVerifyPhoneUseCase() {
        /** @var IChallengeRepository&\PHPUnit\Framework\MockObject\MockObject $challengeRepository */
        $challengeRepository = $this->createMock(IChallengeRepository::class);
        $challengeFoundMock = new Challenge();
        $challengeFoundMock->setChallengeCode(808080);
        $challengeFoundMock->setChallengeEmail("example@email.com");
        $challengeFoundMock->setChallengeId("emailChallenge:215419");
        $challengeRepository->method('getChallenge')->willReturn($challengeFoundMock);
        /** @var IUserRepository&\PHPUnit\Framework\MockObject\MockObject $userRepository */
        $userRepository = $this->createMock(IUserRepository::class);
        $challengeRepository->expects($this->once())
            ->method('killChallenge');
        $verifyChallengePhoneUseCase = new VerifyChallengePhoneUseCase($challengeRepository, $userRepository);
        $response = $verifyChallengePhoneUseCase->execute(['challengeId' => 'emailChallenge:215419', 'challengeCode' => 808080]);
        $this->assertTrue($response['status']);
    }
}