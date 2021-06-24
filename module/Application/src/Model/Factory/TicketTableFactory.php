<?php


namespace Application\Model\Factory;

use Application\Model\Ticket;
use Application\Model\TicketTable;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TicketTableFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get(Adapter::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Ticket());

        $tableGateway = new TableGateway('tickets', $adapter, null, $resultSetPrototype);

        return new TicketTable($tableGateway);
    }
}