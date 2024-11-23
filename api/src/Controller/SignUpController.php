<?php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController
{
    #[Route('/SignUp', name: 'SignUp', methods: ['GET'])]
    public function signUp(Request $request): Response
    {
        $data = json_decode(($request->getContent()), true);
        if(!isset($data['firstName']) || strlen($data['firstName']) > 20 || strlen($data['firstName']) < 3 || strlen($data['firstName']) == null || preg_match('/[^a-zA-Z]/', $data['firstName'])){
            return new Response('Invalid input data 1', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['lastName']) || strlen($data['lastName']) > 30 || strlen($data['lastName'])  < 3 || strlen($data['lastName'])  == null || preg_match('/[^a-zA-Z]/', $data['lastName'])){
            return new Response('Invalid input data 2', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['email']) || $data['email'] == null || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return new Response('Invalid input data 3', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['password']) || $data['password'] == null || strlen($data['password']) < 12){
            return new Response('Invalid input data 4', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['phoneNumber']) || $data['phoneNumber'] == null || !preg_match('/^[0-9]{10}+$/', $data['phoneNumber'])){
            return new Response('Invalid input data 5 ', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['address']) || $data['address'] == null || strlen($data['address']) < 10){
            return new Response('Invalid input data 6', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['city']) || $data['city'] == null || strlen($data['city']) < 3){
            return new Response('Invalid input data 7', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['state']) || $data['state'] == null || strlen($data['state']) < 3){
            return new Response('Invalid input data 8', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['zipCode']) || $data['zipCode'] == null || !preg_match('/^[0-9]{5}+$/', $data['zipCode'])){
            return new Response('Invalid input data 9', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['country']) || $data['country'] == null || strlen($data['country']) < 3){
            return new Response('Invalid input data 10', Response::HTTP_BAD_REQUEST);      
        }
        if(!isset($data['birthDate']) || $data['birthDate'] == null || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}+$/', $data['birthDate'])){
            return new Response('Invalid input data', Response::HTTP_BAD_REQUEST);
        }


        return new Response("ok",200);
    }
}