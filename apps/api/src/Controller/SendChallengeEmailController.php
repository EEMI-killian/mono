<?php

namespace App\Controller;

use App\Repository\Challenge\ChallengeRepository;
use App\SendChallengeEmail\SendChallengeEmailUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Predis\Client as PredisClient;

class SendChallengeEmailController {

    #[Route('/SendChallengeEmail', name: 'SendChallengeEmail', methods: ['POST'])]


    public function sendChallengeEmail(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email'])) {
            return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        }
        $challengeRepository = new ChallengeRepository(new PredisClient());
        $sendChallengeEmail = new SendChallengeEmailUseCase($challengeRepository);
        $sendChallengePhoneArgs = [
            'email' => $data['email']
        ];
        $result = $sendChallengeEmail->execute($sendChallengePhoneArgs,$_ENV['PHONE_NUMBER_IS_FAKE']);
        $result = json_decode($result, true);
        if($result["status"] === false){
            return new Response($result["error"], Response::HTTP_BAD_REQUEST);
        }
        return new Response(json_encode($result), Response::HTTP_OK);
    }
}