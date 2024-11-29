<?php 

namespace App\VerifyChallengePhone;

use App\Repository\Challenge\ChallengeRepository;
use App\Repository\User\IUserRepository;

class VerifyChallengePhoneUseCase
{
    private ChallengeRepository $challengeRepository;
    private IUserRepository $userRepository;

    public function __construct(ChallengeRepository $challengeRepository, IUserRepository $userRepository)
    {
        $this->challengeRepository = $challengeRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(array $verifyChallengePhoneArgs): array
    {
        $challenge = $this->challengeRepository->getChallenge($verifyChallengePhoneArgs["challengeId"]);
        if($challenge === null){
            return ["status" => false, "error" => "Challenge not found"];
        }
        if($challenge->getChallengeCode() !== $verifyChallengePhoneArgs["challengeCode"]){
            return ["status" => false, "error" => "Invalid challenge code"];
        }
        $verfiedPhoneNumber = $challenge->getChallengeEmail();
        $this->userRepository->verifiedPhone($verfiedPhoneNumber);
        $this->challengeRepository->killChallenge($verifyChallengePhoneArgs["challengeId"]);
        return ["status" => true, "message" => "Phone number verified"];
    }
}