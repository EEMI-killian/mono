<?php 

namespace App\Repository\Challenge;

use App\Entity\Challenge;


interface IChallengeRepository
{
public function setChallenge(Challenge $challenge): void;
public function getChallenge(string $challengeId): ?Challenge;

public function killChallenge(string $challengeId): void;
}