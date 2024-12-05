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
        $this->predisClient->hset($challenge->getChallengeId(), 'challengeCode', $challenge->getChallengeCode());
        $this->predisClient->hset($challenge->getChallengeId(), 'challengeEmail', $challenge->getChallengeEmail());
        $this->predisClient->expire($challenge->getChallengeId(),300 );
    }

    public function getChallenge(string $challengeId): ?Challenge
    {
        $challenge = $this->predisClient->hgetall($challengeId);
        if(empty($challenge)){
            return null;
        }
        $challengeObj = new Challenge();
        $challengeObj->setChallengeId($challengeId);
        $challengeObj->setChallengeCode($challenge['challengeCode']);
        $challengeObj->setChallengeEmail($challenge['challengeEmail']);
        return $challengeObj;
        
    }

    public function killChallenge(string $challengeId): void
    {
        $this->predisClient->del($challengeId);

    }
}