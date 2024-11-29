<?php 
// config/routes.php
use App\Controller\HealthController;
use App\Controller\AnalyseImageController;
use App\Controller\CheckEmailController;
use App\Controller\SignUpController;
use App\Controller\SendChallengePhoneController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('HealthCheck', '/Health')
        ->controller([HealthController::class, 'health'])
    ;
    /**------------------------OPENAI ROUTES------------------------**/
    $routes->add('analyseImage', '/AnalyseImage')
        ->controller([AnalyseImageController::class, 'AnalyseImage'])
    ;
    /**-----------------------OTP-CHALLENGE-ROUTES------------------**/
    $routes->add('SendChallengePhone', '/SendChallengePhone')
        ->controller([SendChallengePhoneController::class, 'SendChallengePhone'])
    ;
    /**-----------------------USER-ROUTES---------------------------**/
    $routes->add('SignUp','/SignUp')
        ->controller([SignUpController::class,'SignUp'])
    ;
    $routes->add('CheckEmail','/CheckEmail')
        ->controller([CheckEmailController::class,'CheckEmail'])
        ;
};