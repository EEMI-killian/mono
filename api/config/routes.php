<?php 
// config/routes.php
use App\Controller\HealthController;
use App\Controller\AnalyseImageController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('healthCheck', '/health')
        ->controller([HealthController::class, 'health'])
    ;
    /**------------------------OPENAI ROUTES------------------------**/
    $routes->add('analyseImage', '/analyseImage')
        ->controller([AnalyseImageController::class, 'analyseImage'])
    ;
};