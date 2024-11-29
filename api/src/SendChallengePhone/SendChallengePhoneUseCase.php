<?php 

namespace App\SendChallengePhone;


use App\Entity\Challenge;
use App\Repository\Challenge\IChallengeRepository;

class SendChallengePhoneUseCase {

    private $challengeRepository;

    public function __construct(IChallengeRepository $challengeRepository)
    {
        $this->challengeRepository = $challengeRepository;
    }


    public function execute(array $sendChallengePhoneArgs, bool $isPhoneFake ): string
    {
        try{
            do {
                $challengeCode = rand(100000, 999999);
            } while (in_array($challengeCode,         [123456, 654321, 111111, 222222, 333333, 444444, 555555, 666666, 777777, 888888, 999999]));
        
        if($isPhoneFake){
            $challengeCode = 808080;
        }
        $challenge = new Challenge();
        $challengeid = "phoneChallenge:".rand(100000, 999999);
        $challenge->setChallengeId($challengeid);
        $challenge->setChallengeCode($challengeCode);
        $challenge->setChallengeEmail($sendChallengePhoneArgs['email']);
        $this->challengeRepository->setChallenge($challenge);
        return json_encode(["status" => true ,"challengeId" => $challenge->getChallengeId()]);
    } catch (\Exception $e) {
        return json_encode(["status" => false, "error" => $e->getMessage()]);
    }
    }

}




