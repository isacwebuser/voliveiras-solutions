<?php

namespace Auth\Controller\Factory;

use Auth\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get(AuthenticationService::class);

        return new IndexController($authenticationService);
    }
}