<?php

namespace ContainerOVIUfTP;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getHealthControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\HealthController' shared autowired service.
     *
     * @return \App\Controller\HealthController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/Controller/HealthController.php';

        return $container->services['App\\Controller\\HealthController'] = new \App\Controller\HealthController();
    }
}
