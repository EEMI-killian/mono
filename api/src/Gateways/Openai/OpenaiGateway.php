<?php
namespace App\Gateways\Openai;

use Symfony\Component\HttpClient\HttpClient;

class OpenaiGateway implements IOpenaiGateway
{
    private string $OPENAI_API_KEY;

    public function __construct(string $OPENAI_API_KEY)
    {
        $this->OPENAI_API_KEY = $OPENAI_API_KEY;
    }

    public function analyseImage(array $data): string
    {
        $client = HttpClient::create();

        $response = $client->request(
            'POST',
            'https://api.openai.com/v1/chat/completions',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->OPENAI_API_KEY}",
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $data['prompt']
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => $data['url']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'max_tokens' => 300
                ]
            ]
        );

        $content = $response->getContent();
        return $content;
    }
}