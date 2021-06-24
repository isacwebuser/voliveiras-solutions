<?php


namespace Core\Factories;

use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TransportSmtpFactory implements FactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $transport = new SmtpTransport();
        $options = new SmtpOptions($config['mail']);
        $transport->setOptions($options);

        return $transport;

    }
}