<?php

namespace App\Services\Ai;

interface AiServiceInterface
{
    public function analyseImage(string $base64Image): string;
}
