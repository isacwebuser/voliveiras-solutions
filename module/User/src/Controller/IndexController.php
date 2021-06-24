<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\NewPassword;
use User\Form\UserForm;
use User\Model\UserTable;

class IndexController extends AbstractActionController
{

    private $userForm;
    private $userTable;

    public function __construct(UserForm $userForm, UserTable $userTable)
    {
        $this->userForm = $userForm;
        $this->userTable = $userTable;
    }

    public function registerAction()
    {
        $this->layout()->setTemplate('user/template_layout/layout_auth');


        if ($this->getRequest()->isPost()){
            $this->userForm->setData($this->getRequest()->getPost());
            if ($this->userForm->isValid()){
                $data = $this->userForm->getData();
                try {
                    $user = $this->userTable->save($data);

                    $this->getEventManager()->trigger(
                        __FUNCTION__ . '.post',
                        $this,
                        ['data' => $user]
                    );

                    $this->flashMessenger()->addSuccessMessage(
                        sprintf('Confirme seu registro no email "%s"', $data['email'])
                    );

                } catch (Exception $exception) {
                    $this->flashMessenger()->addErrorMessage($exception->getMessage());
                }
                return $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'form' => $this->userForm->prepare()
        ]);
    }

    public function recoveredPasswordAction()
    {
        $this->layout()->setTemplate('user/template_layout/layout_auth');
        $form = new NewPassword();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $email = $form->getData()['email'];

                $this->getEventManager()->trigger(
                    __FUNCTION__ . '.post',
                    $this,
                    ['data' => $email]
                );

                $this->flashMessenger()->addSuccessMessage(
                    'Usuário localizado. Confirme o recebimento do email.');
            }
        }
        return new ViewModel([
            'form' => $form->prepare()
        ]);
        return $this->redirect()->refresh();
    }

    public function newPasswordAction()
    {
        try {
            $token = $this->params()->fromRoute('token', null);
            $user = $this->userTable->getUserByToken($token);

            $this->layout()->setTemplate('user/template_layout/layout_auth');

            if ($this->getRequest()->isPost()) {
                $this->userForm->setValidationGroup('password', 'replypassword');
                $this->userForm->setData($this->getRequest()->getPost());

                if ($this->userForm->isValid()) {
                    $password = $this->userForm->getData()['password'];

                    try {
                        $this->userTable->save([
                            'iduser' => $user->iduser,
                            'password' => $password
                        ]);
                        $this->flashMessenger()->addSuccessMessage(
                            'Senha alterada com sucesso!'
                        );

                        return $this->redirect()->toRoute('auth.login');

                    } catch (\Exception $exception) {

                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                        return $this->redirect()->refresh();
                    }
                }
            }

            return new ViewModel([
                'form' => $this->userForm->prepare()
            ]);

        } catch (\Exception $exception) {

            $this->flashMessenger()->addErrorMessage($exception->getMessage());

            return $this->redirect()->toRoute('auth.login');
        }
    }

    public function confirmedEmailAction()
    {
        $token = $this->params()->fromRoute('token', null);

        try {
            /**
             * @var $user \User\Model\User
             */
            $user = $this->userTable->getUserByToken($token);

            if (empty($user)) {

                $this->flashMessenger()->addErrorMessage('Token expirado!');

                return $this->redirect()->toRoute('auth.login');
            };

            $this->userTable->save($user->getArrayCopy());

            $this->flashMessenger()->addSuccessMessage(
                sprintf('Conta liberada com sucesso')
            );

        } catch (\Exception $exception) {
            $this->flashMessenger()->addErrorMessage($exception->getMessage());
        }

        return $this->redirect()->toRoute('auth.login');
    }
}
