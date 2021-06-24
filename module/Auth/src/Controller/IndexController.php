<?php


namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Authentication\Adapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Model\SessionTable;
use User\Model\UserTable;

class IndexController extends AbstractActionController
{
    private $authenticateService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticateService = $authenticationService;
    }

    public function loginAction()
    {
        $this->layout()->setTemplate('auth/template_layout/layout_auth');
        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()){
                /**
                 * @var $adapter \Auth\Authentication\Adapter
                 */
                $adapter = $this->authenticateService->getAdapter();
                $adapter->setEmail($form->getData()['email'])
                     ->setPassword($form->getData()['password']);

                 if (! $this->authenticateService->authenticate()->isValid()) {
                     $message = $this->authenticateService->authenticate()->getMessages();
                     $this->flashMessenger()->addErrorMessage($message[0]);
                     return $this->redirect()->refresh();
                 }

                 return $this->redirect()->toRoute('dashboard');
            }
        }
        return new ViewModel([
            'form' => $form->prepare()
        ]);

    }

    public function logoutAction()
    {
        $this->adapter->clearIdentity();
        $this->flashMessenger()->addInfoMessage('Sessão expirada!');
        return $this->redirect()->toRoute('auth.login');
    }
}