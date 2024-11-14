<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\AnalyseImageUseCase;
use App\Gateways\Openai\OpenaiGateway;
use Symfony\Component\HttpFoundation\Request;

class AnalyseImageController 
{
    #[Route('/analyseImage', name: 'analyseImage', methods: ['GET'])]
    public function analyseImage(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);

        // if (!isset($data['url']) || !isset($data['prompt']) || !isset($data['type'])) {
        //     return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        // }
        
        $openaiGateway = new OpenaiGateway($_ENV['OPENAI_API_KEY']);
        $analyseImageUseCase = new AnalyseImageUseCase();
        $response = $analyseImageUseCase->execute($data);
        if (json_decode($response, true)['status'] === false) {
            return new Response(json_decode($response, true)['error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }else{
            return new Response($response, Response::HTTP_OK);
        }
    }
}