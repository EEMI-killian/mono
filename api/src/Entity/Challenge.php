<?php

namespace App\Entity;

use App\Repository\Challenge\ChallengeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $challengeId = null;

    #[ORM\Column]
    private ?int $challengeCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallengeId(): ?int
    {
        return $this->challengeId;
    }

    public function setChallengeId(int $challengeId): static
    {
        $this->challengeId = $challengeId;

        return $this;
    }

    public function getChallengeCode(): ?int
    {
        return $this->challengeCode;
    }

    public function setChallengeCode(int $challengeCode): static
    {
        $this->challengeCode = $challengeCode;

        return $this;
    }
}
