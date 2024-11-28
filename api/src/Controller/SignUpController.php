<?php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\SignUp\SignUpUseCase;


class SignUpController
{
    private SignUpUseCase $signUpUseCase;

    public function __construct(SignUpUseCase $signUpUseCase)
    {
        $this->signUpUseCase = $signUpUseCase;
    }

    #[Route('/SignUp', name: 'SignUp', methods: ['POST'])]
    public function signUp(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);
        
        $requiredFields = [
            'firstName', 'lastName', 'email', 'password', 'phoneNumber', 
            'address', 'city', 'state', 'zipCode', 'country', 'birthDate'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);      
            }
        }
    
        $response = $this->signUpUseCase->execute($data);
        
        if (json_decode($response, true)['status'] === false) {
            return new Response(json_decode($response, true)['error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new Response($response, Response::HTTP_OK);
    }
}