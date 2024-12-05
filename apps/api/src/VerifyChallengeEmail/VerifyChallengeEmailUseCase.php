<?php 

namespace App\VerifyChallengeEmail;

use App\Repository\Challenge\IChallengeRepository;
use App\Repository\User\IUserRepository;

class VerifyChallengeEmailUseCase
{
    private IChallengeRepository $challengeRepository;
    private IUserRepository $userRepository;

    public function __construct(IChallengeRepository $challengeRepository, IUserRepository $userRepository)
    {
        $this->challengeRepository = $challengeRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(array $verifyChallengeEmailArgs): array
    {
        $challenge = $this->challengeRepository->getChallenge($verifyChallengeEmailArgs["challengeId"]);
        if($challenge === null){
            return ["status" => false, "error" => "Challenge not found"];
        }
        if($challenge->getChallengeCode() !== $verifyChallengeEmailArgs["challengeCode"]){
            return ["status" => false, "error" => "Invalid challenge code"];
        }
        $verifiedEmail = $challenge->getChallengeEmail();
        $this->userRepository->verifiedEmail($verifiedEmail);
        $this->challengeRepository->killChallenge($verifyChallengeEmailArgs["challengeId"]);
        return ["status" => true, "message" => "Phone number verified"];
    }
}