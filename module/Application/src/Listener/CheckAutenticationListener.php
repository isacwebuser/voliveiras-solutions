<?php

namespace Application\Listener;

use Application\Controller\DashboardController;
use Application\Controller\TicketController;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;

class CheckAutenticationListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvent = $events->getSharedManager();

        $this->listeners[] = $sharedEvent->attach(
            DashboardController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'dispatch'],
            $priority
        );

        $this->listeners[] = $sharedEvent->attach(
            TicketController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'dispatch'],
            $priority
        );
    }

    public function dispatch(Event $event)
    {
        /**
         * @var $controller AbstractActionController
         */

        $controller = $event->getTarget();

        if (! $controller->identity()) {

            $controller->flashMessenger()->addInfoMessage('O acesso ao ambiente é permitido após a autenticação');

            return $controller->redirect()->toRoute('auth.login');
        }
    }
}