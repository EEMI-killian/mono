<?php

namespace App\Services\Ai;

interface AiServiceInterface
{
    // return a json response
    public function analyseImage(string $base64Image): string;
}
