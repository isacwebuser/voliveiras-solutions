<?php

namespace Auth\Authentication\Factory;

use Auth\Authentication\Adapter;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Model\UserTable;

class AuthenticationFactory implements FactoryInterface
{
    /**
     * {@inheritDoc }
     *
     */

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $session = new Session();

        $user = $container->get(UserTable::class);

        return new AuthenticationService($session, new Adapter($user));
    }
}