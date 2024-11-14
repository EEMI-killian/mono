<?php
namespace App\AnalyseImage;
use App\Gateways\Openai\IOpenaiGateway;

class AnalyseImageUseCase
{
    private IOpenaiGateway $openaiGateway;
    function __construct(IOpenaiGateway $openaiGateway) {
        $this->openaiGateway = $openaiGateway;
      }

    public function execute(array $data): string
    {
        try{
        $response =  $this->openaiGateway->analyseImage($data);
        return json_encode(["status" => true, "response" => $response]);
        } catch (\Exception $e) {
            return json_encode(["status" => false, "error" => $e->getMessage()]);
        }
    }
}
