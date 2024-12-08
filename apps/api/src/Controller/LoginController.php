<?php
namespace App\Controller;

use App\Login\LoginUseCase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LoginController 
{
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase)
    {
        $this->loginUseCase = $loginUseCase;
    }

    #[Route('/Login', name: 'Login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);

        $requiredFields = [
            'email', 'password'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
            }
        }

        $email = $data['email'];
        $password = $data['password'];

        $responseData = $this->loginUseCase->execute($email, $password);
        if ($responseData['status'] === false) {
            return new Response($responseData['error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(json_encode($responseData), Response::HTTP_OK);
    }

}