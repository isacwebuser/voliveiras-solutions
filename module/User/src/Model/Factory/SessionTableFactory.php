<?php


namespace User\Model\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SaveHandler\DbTableGateway;
use Laminas\Session\SaveHandler\DbTableGatewayOptions;
use Laminas\Session\SessionManager;
use User\Model\Session;
use User\Model\SessionTable;

class SessionTableFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get(Adapter::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Session());
        $tableGateway = new TableGateway('session', $adapter, null, $resultSetPrototype);
        $saveHandler  = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
        $manager      = new SessionManager();
        $manager->setSaveHandler($saveHandler);

        return new SessionTable($tableGateway);
    }
}