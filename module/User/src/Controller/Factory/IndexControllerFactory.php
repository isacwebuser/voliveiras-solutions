<?php

namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Form\UserForm;
use User\Model\UserTable;
use User\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface {
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $adapter = $container->get(Adapter::class);

        $userForm = new UserForm($adapter);
        $userTable = $container->get(UserTable::class);

        return new IndexController($userForm, $userTable);
    }
}
