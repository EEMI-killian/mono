<?php 

namespace App\Repository\Challenge;


use App\Entity\Challenge;
use Predis\Client as PredisClient;


class ChallengeRepository implements IChallengeRepository
{
    private PredisClient $predisClient ;

    public function __construct($predisClient)
    {
        $this->predisClient = $predisClient;
    }

    public function setChallenge(Challenge $challenge): void
    {
        $this->predisClient->set($challenge->getChallengeId(), json_encode($challenge->getChallengeCode()), 'EX', 300);
    }

    public function getChallenge(string $challengeId): ?Challenge
    {
        $challenge = $this->predisClient->get($challengeId);
        if($challenge === null){
            return null;
        }
        $challengeResponse = new Challenge(json_decode($challenge, true));

        return $challengeResponse;
    }

    public function killChallenge(string $challengeId): void
    {
        $this->predisClient->del($challengeId);
    }
}