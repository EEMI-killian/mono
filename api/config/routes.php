<?php 
// config/routes.php
use App\Controller\HealthController;
use App\Controller\AnalyseImageController;
use App\Controller\VerifyPhoneNumberController;
use App\Controller\SignUpController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('HealthCheck', '/Health')
        ->controller([HealthController::class, 'health'])
    ;
    /**------------------------OPENAI ROUTES------------------------**/
    $routes->add('analyseImage', '/analyseImage')
        ->controller([AnalyseImageController::class, 'VnalyseImage'])
    ;
    /**-----------------------OTP-CHALLENGE-ROUTES------------------**/
    $routes->add('VerifyPhoneNumber', '/VerifyPhoneNumber')
        ->controller([VerifyPhoneNumberController::class, 'VerifyPhoneNumber'])
    ;
    /**-----------------------USER-ROUTES---------------------------**/
    $routes->add('SignUp','/SignUp')
        ->controller([SignUpController::class,'SignUp'])
        ;
};