<?php
// src/Controller/BlogController.php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthController 
{
    #[Route('/health', name: 'healthCheck', methods: ['GET'])]
    public function health(): Response
    {
        return new Response("ok",200);
    }
}