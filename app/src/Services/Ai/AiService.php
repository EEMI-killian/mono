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
                                ['type' => 'text', 'text' => "Analyze the given image and provide an in-depth description of the outfit worn by the person(s) present, including clothing, footwear, and accessories. Focus only on the outfit, without describing the general scene or background.

For each detected item (clothing, footwear, accessories), return the following information in JSON format:

category: The general category of the item (e.g., 'clothing', 'footwear', 'accessory').
type: The specific type of item (e.g., 't-shirt', 'pants', 'jacket', 'sneakers', 'watch', 'hat', 'backpack', 'gloves', etc.).
color: The main color of the detected item, or null if not identifiable.
brand: The brand of the item if visible, or null if no brand is detected.
material: The fabric or material of the item (e.g., 'cotton', 'denim', 'leather', 'wool', 'metal', 'synthetic'), or null if not identifiable.
fit: The style or fit of the clothing item (e.g., 'oversized', 'slim fit', 'baggy', 'cropped', 'regular fit'), or null if not applicable.
length: The length of the item (e.g., 'short', 'medium', 'long', 'ankle-length', 'waist-length'), or null if not applicable.
closure: The type of closure or fastening mechanism (e.g., 'zipper', 'buttons', 'laces', 'slip-on', 'buckle', 'elastic'), or null if not applicable.
condition: The apparent condition of the item (e.g., 'new', 'worn', 'vintage', 'distressed', 'faded'), or null if not identifiable.
extra_details: Additional notable features such as logos, text, embroidery, visible design elements, or unique characteristics (e.g., 'Nike logo on the chest', 'gold buckle', 'silver chain', 'zippered pockets').
If multiple items are present, list each item individually with its respective attributes. If no items are detected, return an empty object {}.

Additionally, provide:

A structured and detailed textual summary of the outfit, listing the items in a logical order (upper body, lower body, footwear, accessories). This should include details about the combination of items, their fit, materials, colors, and how they work together.
A global outfit analysis, identifying the overall style (e.g., 'casual', 'sporty', 'streetwear', 'formal', 'vintage', 'minimalist') and how the different elements contribute to that aesthetic."],
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
                                                'brand' => ['type' => 'string'],
                                                'color' => ['type' => 'string'],
                                                'fit' => ['type' => 'string'],
                                                'type' => ['type' => 'string'],
                                                'material' => ['type' => 'string'],
                                            ],
                                            'required' => ['name', 'brand', 'color', 'fit', 'type', 'material'],
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

    public function generateSuggestionAdminDashboard(string $field, string $type, int $id): string
    {
        $messages = [
            "user" => "L'utilisateur ID {$id} a un champ '{$field}' manquant. Pouvez-vous suggérer une valeur appropriée pour ce champ?",
            "outfit" => "L'outfit ID {$id} a un champ '{$field}' manquant. Pouvez-vous suggérer une valeur appropriée pour ce champ?",
            "item" => "L'item ID {$id} a un champ '{$field}' manquant. Pouvez-vous suggérer une valeur appropriée pour ce champ?",
        ];

        $prompt = $messages[$type] ?? "Problème détecté sur {$type} ID {$id}. Pouvez-vous suggérer une valeur appropriée pour le champ '{$field}' en fonction des colonnes existantes (par exemple, marque, couleur, type, coupe, matière, etc.)?";

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
                    'max_tokens' => 75
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
