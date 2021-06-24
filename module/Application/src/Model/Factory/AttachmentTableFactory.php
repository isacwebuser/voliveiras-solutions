<?php


namespace Application\Model\Factory;

use Application\Model\Attachment;
use Application\Model\AttachmentTable;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AttachmentTableFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get(Adapter::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Attachment());

        $tableGateway = new TableGateway('attachments', $adapter, null, $resultSetPrototype);

        return new AttachmentTable($tableGateway);
    }
}