<?php

namespace App\Controller;

use App\Repository\Challenge\ChallengeRepository;
use App\SendChallengePhone\SendChallengePhoneUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Predis\Client as PredisClient;

class SendChallengePhoneController {

    #[Route('/SendChallengePhone', name: 'SendChallengePhone', methods: ['POST'])]


    public function sendChallengePhone(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['phoneNumber']) || !isset($data['email'])) {
            return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        }
        $challengeRepository = new ChallengeRepository(new PredisClient());
        $sendChallengePhone = new SendChallengePhoneUseCase($challengeRepository);
        $sendChallengePhoneArgs = [
            'phoneNumber' => $data['phoneNumber'],
            'email' => $data['email']
        ];
        $result = $sendChallengePhone->execute($sendChallengePhoneArgs,$_ENV['IS_PHONE_FAKE']);
        $result = json_decode($result, true);
        if($result["status"] === false){
            return new Response($result["error"], Response::HTTP_BAD_REQUEST);
        }
        return new Response(json_encode($result), Response::HTTP_OK);
    }
}