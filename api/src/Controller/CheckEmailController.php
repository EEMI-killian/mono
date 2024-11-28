<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\CheckEmail\CheckEmailUseCase;

class CheckEmailController
{
    private CheckEmailUseCase $checkEmailUseCase;

    public function __construct(CheckEmailUseCase $checkEmailUseCase)
    {
        $this->checkEmailUseCase = $checkEmailUseCase;
    }

    #[Route('/CheckEmail', name: 'CheckEmail', methods: ['POST'])]
    public function checkEmail(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);
        
        $response = $this->checkEmailUseCase->execute($data);
        
        return new Response($response, Response::HTTP_OK);
    }
}