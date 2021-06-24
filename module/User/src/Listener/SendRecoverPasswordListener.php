<?php


namespace User\Listener;


use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\ServiceManager;
use User\Controller\IndexController;
use User\Mail\Mail;
use User\Model\User;
use Core\Stdlib\CurrentUrl;
use User\Model\UserTable;

class SendRecoverPasswordListener extends AbstractListenerAggregate
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
            'recoveredPasswordAction.post',
            [$this,'onSendRecoveredPassword'],
            $priority
        );
    }

    public function onSendRecoveredPassword(Event $event)
    {
        /**
         * @var $controller IndexController
         * @var $user \User\Model\User
         * @var $transport \Laminas\Mail\Transport\Smtp
         * @var $userTable \User\Model\UserTable
        **/

        $controller = $event->getTarget();
        $email = $event->getParams()['data'];

        try {
            $userTable = $this->serviceManager->get(UserTable::class);

            $user = $userTable->getUserByEmail($email);

            $user = $userTable->save([
                'iduser' => $user->iduser,
                'email' => $user->email
            ]);

            $transport = $this->serviceManager->get('core.transport.smtp');
            $view = $this->serviceManager->get('View');

            $data = $user->getArrayCopy();
            $data ['ip'] = $controller->getRequest()->getServer('REMOTE_ADDR');
            $data ['host'] = $this->getUrl($controller->getRequest());

            $email = new Mail($transport, $view, 'user/mailer/recover-password');

            $email->setSubject('Nova senha de Usuário voliveiras')
                ->setTo(strtolower(trim($user->email)))
                ->setData($data)
                ->prepareMail()
                ->sendMail();
        } catch (Exception $e){
            echo $e->getTraceAsString();
            die;
        }
    }
}