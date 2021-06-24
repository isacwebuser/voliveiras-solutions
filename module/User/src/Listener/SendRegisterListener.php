<?php


namespace User\Listener;


use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\ServiceManager;
use User\Controller\IndexController;
use User\Mail\Mail;
use Core\Stdlib\CurrentUrl;

class SendRegisterListener extends AbstractListenerAggregate
{
    use CurrentUrl;

    private $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(
            IndexController::class,
            'registerAction.post',
            [$this,'onSendRegister'],
            $priority
        );
    }

    public function onSendRegister(Event $event)
    {
        /**
         * @var $controller IndexController
         * @var $user \User\Model\User
         * @var $transport \Laminas\Mail\Transport\Smtp
        **/

        $controller = $event->getTarget();
        $user = $event->getParams()['data'];
        $transport = $this->serviceManager->get('core.transport.smtp');
        $view = $this->serviceManager->get('View');
        $data = $user->getArrayCopy();
        $data ['ip'] = $controller->getRequest()->getServer('REMOTE_ADDR');
        $data ['host'] = $this->getUrl($controller->getRequest());
        $email = new Mail($transport, $view, 'user/mailer/register');

        $email->setSubject('Cadastro de Usuário voliveiras')
            ->setTo(strtolower(trim($user->email)))
            ->setData($data)
            ->prepareMail()
            ->sendMail();
    }
}