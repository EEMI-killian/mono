<?php 


namespace App\Controller;

use App\Repository\Challenge\ChallengeRepository;
use App\Repository\User\UserRepository;
use App\VerifyChallengePhone\VerifyChallengePhoneUseCase;
use Doctrine\Persistence\ManagerRegistry;
use Predis\Client as PredisClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



class VerifyChallengePhoneController
{
    #[Route('/VerifyChallengePhone', name: 'VerifyChallengePhone', methods: ['GET'])]

    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function verifyChallengePhone(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['challengeId']) || !isset($data['challengeCode'])) {
            return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        }
        $challengeRepository = new ChallengeRepository(new PredisClient());
        $verifyChallengePhone = new VerifyChallengePhoneUseCase($challengeRepository, new UserRepository($this->registry));
        $verifyChallengePhoneArgs = [
            'challengeId' => $data['challengeId'],
            'challengeCode' => $data['challengeCode']
        ];
        $result = $verifyChallengePhone->execute($verifyChallengePhoneArgs);
        if($result === false){
            return new Response('Invalid challenge code', Response::HTTP_BAD_REQUEST);
        }
        return new Response('Challenge code verified', Response::HTTP_OK);

    }
}