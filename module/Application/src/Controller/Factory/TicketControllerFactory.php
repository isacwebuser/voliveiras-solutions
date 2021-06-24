<?php


namespace Application\Controller\Factory;


use Application\Controller\TicketController;
use Application\Model\AttachmentTable;
use Application\Form\TicketForm;
use Application\Model\TicketTable;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TicketControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get(Adapter::class);

        $ticketForm = new TicketForm($adapter);
        $ticketTable = $container->get(TicketTable::class);
        $attachmentTable = $container->get(AttachmentTable::class);

        return new TicketController($ticketForm, $ticketTable,$attachmentTable);
    }
}