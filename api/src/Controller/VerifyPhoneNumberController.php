<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\VerifyPhoneNumber\VerifyPhoneNumberUseCase;
use App\Gateways\Twilio\TwilioGateway;
use Symfony\Component\HttpFoundation\Request;


class VerifyPhoneNumberController
{
    #[Route('/VerifyPhoneNumber', name: 'verifyPhoneNumber', methods: ['POST'])]
    public function verifyPhoneNumber(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);

        if (!isset($data['phoneNumber']) ) {
            return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        }
        $args = [
            'phoneNumber' => $data['phoneNumber']
        ];
        
        $twilioGateway = new TwilioGateway($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN'], $_ENV['TWILIO_SERVICE_SID']);
        $sendOtpSmsUseCase = new VerifyPhoneNumberUseCase($twilioGateway);
        $response = $sendOtpSmsUseCase->execute($args);
        if (json_decode($response, true)['status'] === false) {
            return new Response(json_decode($response, true)['error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }else{
            return new Response($response, Response::HTTP_OK);
        }
    }
}