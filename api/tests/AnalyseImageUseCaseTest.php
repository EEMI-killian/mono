<?php 

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\AnalyseImage\AnalyseImageUseCase;
use App\Gateways\Openai\IOpenaiGateway;

class AnalyseImageUseCaseTest extends WebTestCase
{
    public function testHappyPath(): void
    {
        /** @var IOpenaiGateway&\PHPUnit\Framework\MockObject\MockObject $openaiGateway */
        $openaiGateway = $this->createMock(IOpenaiGateway::class);
        $openaiGateway->expects($this->once())
            ->method('analyseImage')
            ->willReturn('{"status": "success"}');

        $analyseImageUseCase = new AnalyseImageUseCase($openaiGateway);
        $response = $analyseImageUseCase->execute(['prompt' => 'prompt', 'url' => 'url']);

        $this->assertIsString($response);
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertEquals(true, $responseData['status']);
        $this->assertArrayHasKey('response', $responseData);
        $this->assertEquals('{"status": "success"}', $responseData['response']);
        
    }

    public function testUnhappyPath(): void
    {
        /** @var IOpenaiGateway&\PHPUnit\Framework\MockObject\MockObject $openaiGateway */
        $openaiGateway = $this->createMock(IOpenaiGateway::class);
        $openaiGateway->expects($this->once())
            ->method('analyseImage')
            ->willThrowException(new \Exception('error'));

        $analyseImageUseCase = new AnalyseImageUseCase($openaiGateway);
        $response = $analyseImageUseCase->execute(['prompt' => 'prompt', 'url' => 'url']);

        $this->assertIsString($response);
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertEquals(false, $responseData['status']);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('error', $responseData['error']);
    }
}