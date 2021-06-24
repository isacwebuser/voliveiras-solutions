<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

namespace Application;

use Application\Listener\CheckAutenticationListener;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManager;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;

class Module implements BootstrapListenerInterface
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */

    public function onBootstrap(EventInterface $e)
    {
        /**
         * @var $eventManager EventManager
         */
        $eventManager = $e->getApplication()->getEventManager();

        (new CheckAutenticationListener())->attach($eventManager, 99);
    }
}
