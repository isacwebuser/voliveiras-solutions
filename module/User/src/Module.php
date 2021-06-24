<?php

namespace User;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use User\Listener\SendRecoverPasswordListener;
use User\Listener\SendRegisterListener;

class Module implements BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    /**
     *
     * {@inheritDoc}
     */

    public function onBootstrap(EventInterface $e)
    {
        /**
         * @var $eventManager \Laminas\EventManager\EventManager
         * @var $serviceManager \Laminas\ServiceManager\ServiceManager
         */

        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        (new SendRegisterListener($serviceManager))->attach($eventManager, 100);
        (new SendRecoverPasswordListener($serviceManager))->attach($eventManager, 100);
    }
}