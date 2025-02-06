
<?php

use PHPUnit\Framework\TestCase;
use App\Controller\OutfitController;
use Symfony\Component\BrowserKit\Request;

final class OutfitControllerTest extends TestCase
{
    public function testOufitController(): void
    {
        $http_client = new Request([], [], [], [], [], [], '');
        $openaiApiKey = 'mock';
        $promptText = 'mock';

        $outfitController = new OutfitController($http_client, $openaiApiKey, $promptText);
    }
}
