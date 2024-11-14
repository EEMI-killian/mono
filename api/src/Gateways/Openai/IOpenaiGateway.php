<?php 
namespace App\Gateways\Openai;

interface IOpenaiGateway
{
    public function analyseImage(array $data): string;
}