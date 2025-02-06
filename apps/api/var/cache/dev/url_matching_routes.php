<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/AnalyseImage' => [
            [['_route' => 'AnalyseImage', '_controller' => 'App\\Controller\\AnalyseImageController::analyseImage'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'analyseImage', '_controller' => ['App\\Controller\\AnalyseImageController', 'AnalyseImage']], null, null, null, false, false, null],
        ],
        '/Health' => [[['_route' => 'HealthCheck', '_controller' => ['App\\Controller\\HealthController', 'health']], null, null, null, false, false, null]],
        '/SendChallengePhone' => [[['_route' => 'SendChallengePhone', '_controller' => ['App\\Controller\\SendChallengePhoneController', 'SendChallengePhone']], null, null, null, false, false, null]],
        '/SendChallengeEmail' => [[['_route' => 'SendChallengeEmail', '_controller' => ['App\\Controller\\SendChallengeEmailController', 'SendChallengeEmail']], null, null, null, false, false, null]],
        '/VerifyChallengePhone' => [[['_route' => 'VerifyChallengePhone', '_controller' => ['App\\Controller\\VerifyChallengePhoneController', 'VerifyChallengePhone']], null, null, null, false, false, null]],
        '/VerifyChallengeEmail' => [[['_route' => 'VerifyChallengeEmail', '_controller' => ['App\\Controller\\VerifyChallengeEmailController', 'VerifyChallengeEmail']], null, null, null, false, false, null]],
        '/SignUp' => [[['_route' => 'SignUp', '_controller' => ['App\\Controller\\SignUpController', 'SignUp']], null, null, null, false, false, null]],
        '/CheckEmail' => [[['_route' => 'CheckEmail', '_controller' => ['App\\Controller\\CheckEmailController', 'CheckEmail']], null, null, null, false, false, null]],
        '/Login' => [[['_route' => 'Login', '_controller' => ['App\\Controller\\LoginController', 'Login']], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
