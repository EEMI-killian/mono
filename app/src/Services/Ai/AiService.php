<?php

namespace App\Services\Ai;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\Ai\AiServiceInterface;



class AiService implements AiServiceInterface
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
                    'model' => 'gpt-4o-2024-08-06',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => $this->promptText],
                                ['type' => 'image_url', 'image_url' => ['url' => "data:image/jpeg;base64,{$base64Image}"]]
                            ]
                        ]
                    ],
                    'response_format' => [
                        'type' => 'json_schema',
                        'json_schema' => [
                            'name' => 'outfit_items',
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'name' => ['type' => 'string'],
                                                'brand' => ['type' => 'string'],
                                                'color' => ['type' => 'string'],
                                                'fit' => ['type' => 'string'],
                                                'type' => ['type' => 'string'],
                                                'material' => ['type' => 'string']
                                            ],
                                            'required' => ['name', 'brand', 'color', 'fit', 'type', 'material'],
                                            'additionalProperties' => false
                                        ]
                                    ]
                                ],
                                'required' => ['items'],
                                'additionalProperties' => false
                            ],
                            'strict' => true
                        ]
                    ],
                    'max_tokens' => 300
                ]
            ]

        );


        if ($response->getStatusCode() !== 200) {
            var_dump($response->getContent());
            throw new \Exception('AI request failed');
        }

        $content = $response->toArray();
        return $content['choices'][0]['message']['content'];
    }
    public function generateOutfitSuggestionsWithUserInput(array $userOutfits, array $userWardrobe, string $userInput): string
    {
        $prompt = "Based on the user's outfits and wardrobe, and the following user input: '{$userInput}', suggest new outfit combinations. Here are the user's outfits: " . json_encode($userOutfits) . ". Here is the user's wardrobe: " . json_encode($userWardrobe) . ".";

        $response = $this->httpClient->request(
            'POST',
            'https://api.openai.com/v1/chat/completions',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->openaiApiKey}",
                ],
                'json' => [
                    'model' => 'gpt-4o-2024-08-06',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'response_format' => [
                        'type' => 'json_schema',
                        'json_schema' => [
                            'name' => 'outfit_suggestions',
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'suggestions' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'name' => ['type' => 'string'],
                                                'items' => [
                                                    'type' => 'array',
                                                    'items' => [
                                                        'type' => 'object',
                                                        'properties' => [
                                                            'name' => ['type' => 'string'],
                                                            'brand' => ['type' => 'string'],
                                                            'color' => ['type' => 'string'],
                                                            'fit' => ['type' => 'string'],
                                                            'type' => ['type' => 'string'],
                                                            'material' => ['type' => 'string']
                                                        ],
                                                        'required' => ['name', 'brand', 'color', 'fit', 'type', 'material'],
                                                        'additionalProperties' => false
                                                    ]
                                                ]
                                            ],
                                            'required' => ['name', 'items'],
                                            'additionalProperties' => false
                                        ]
                                    ]
                                ],
                                'required' => ['suggestions'],
                                'additionalProperties' => false
                            ],
                            'strict' => true
                        ]
                    ],
                    'max_tokens' => 300
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            var_dump($response->getContent());
            throw new \Exception('AI request failed');
        }

        $content = $response->toArray();
        return $content['choices'][0]['message']['content'];
    }

}
