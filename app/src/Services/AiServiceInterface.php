<?php

namespace App\Services;

interface AiServiceInterface
{
    public function analyseImage(string $base64Image): string;
}
