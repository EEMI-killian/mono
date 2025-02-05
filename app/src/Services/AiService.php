<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class AiService
{

    private HttpClientInterface $httpClient;
    private $openaiApiKey;
    private $promptText;

    public function __construct(HttpClientInterface $httpClient, string $openaiApiKey, string $promptText)
    {
        $this->httpClient = $httpClient;
        $this->openaiApiKey = $openaiApiKey;
        $this->promptText = $promptText;
    }


    public function analyseImage(string $base64Image): string
    {

        $response = $this->httpClient->request(
            'POST',
            'https://api.openai.com/v1/chat/completions',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->openaiApiKey}",
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $this->promptText
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => "data:image/jpeg;base64,{$base64Image}"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'max_tokens' => 300
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('AI request failed');
        }



        $content = $response->toArray();
        return $content['choices'][0]['message']['content'];
    }
}
