<?php 

namespace App\VerifyChallengeEmail;

use App\Repository\Challenge\ChallengeRepository;
use App\Repository\User\IUserRepository;

class VerifyChallengeEmailUseCase
{
    private ChallengeRepository $challengeRepository;
    private IUserRepository $userRepository;

    public function __construct(ChallengeRepository $challengeRepository, IUserRepository $userRepository)
    {
        $this->challengeRepository = $challengeRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(array $verifyChallengeEmailArgs): bool
    {
        $challenge = $this->challengeRepository->getChallenge($verifyChallengeEmailArgs["challengeId"]);
        if($challenge === null){
            return false;
        }
        if($challenge->getChallengeCode() !== $verifyChallengeEmailArgs["challengeCode"]){
            return false;
        }
        $verifiedEmail = $challenge->getChallengeEmail();
        $this->userRepository->verifiedEmail($verifiedEmail);
        $this->challengeRepository->killChallenge($verifyChallengeEmailArgs["challengeId"]);
        return true;
    }
}