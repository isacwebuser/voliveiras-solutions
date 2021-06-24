<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Application\Form\TicketForm;
use Application\Model\AttachmentTable;
use Application\Model\Ticket;
use Application\Model\TicketTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Form;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TicketController extends AbstractActionController
{
    private $ticketForm;
    private $ticketTable;
    private $attachmentTable;

    public function __construct(TicketForm $ticketForm, TicketTable $ticketTable, AttachmentTable $attachmentTable)
    {
        $this->ticketForm = $ticketForm;
        $this->ticketTable = $ticketTable;
        $this->attachmentTable = $attachmentTable;
    }

    public function searchTicketAction()
    {
        $paginator = $this->ticketTable->findAll([
            'user' => $this->identity()->id
        ],true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1: $page;
        $paginator->setCurrentPageNumber($page);

        $paginator->setItemCountPerPage(20);

        return new ViewModel([
            'paginator' => $paginator
        ]);
    }
    public function createTicketAction()
    {
        if ($this->getRequest()->isPost()) {

            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $this->ticketForm->setData($post);

            if ($this->ticketForm->isValid()){

                $data = $this->ticketForm->getData();

                try {
                    $data ['user'] = $this->identity()->id;
                    $ticket =  $this->ticketTable->save($data);
                    $this->attachmentTable->saveAttachment($data, $ticket);

                    $this->flashMessenger()->addSuccessMessage('Ticket criado com sucesso.');
                }catch (\Exception $exception ){
                    $this->flashMessenger()->addErrorMessage($exception->getMessage());
                }
                return $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'form' => $this->ticketForm->prepare()
        ]);
    }
    public function editTicketAction()
    {
        $id = (int) $this->params()->fromRoute('id');

        try {
            /**
             * @var $ticket Ticket
             */
            $ticket = $this->ticketTable->getBy([
                'id' => $id
            ]);
            $this->ticketForm->setAttribute('action', $this->url()->fromRoute('ticket', [
                'action' => 'editTicket',
                'id' => $ticket->id
            ]));

            $this->ticketForm->setData($ticket->getArrayCopy());

            if ($this->getRequest()->isPost()) {

                $post = array_merge_recursive(
                    $this->getRequest()->getPost()->toArray(),
                    $this->getRequest()->getFiles()->toArray()
                );
                $this->ticketForm->setData($post);

                if ($this->ticketForm->isValid()){

                    $data = $this->ticketForm->getData();

                    try {
                        $data['id'] = $ticket->id;
                        $ticket = $this->ticketTable->save($data);
                        $this->attachmentTable->saveAttachment($data, $ticket);
                        $this->flashMessenger()->addSuccessMessage('Ticket atualizado com sucesso.');
                        return  $this->redirect()->refresh();
                    } catch (\Exception $exception) {
                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                    }
                }
            }
            return new ViewModel([
                'form' => $this->ticketForm->prepare(),
                'attachment' => $this->attachmentTable->findAll(['ticket' => $ticket->id])
            ]);

        } catch (\Exception $exception) {
            $this->flashMessenger()->addErrorMessage('Registro não localizado');
        }
        return  $this->redirect()->refresh();
    }
    public function deleteTicketAction()
    {
        $id = (int) $this->params()->fromRoute('id');

        try {
            /**
             * @var $ticket TicketForm
             */
            $ticket = $this->ticketTable->getBy(['id' => $id]);
            $form = new Form();
            $csrf = new Csrf('csrf');
            $csrf->setOptions([
                'csrf_options' => [
                    'timeout' => 600
                ]
            ]);
            $form->add($csrf);

            if ($this->getRequest()->isPost()) {

                $form->setData($this->getRequest()->getPost());
                if ($form->isValid()) {
                    try {
                        $this->attachmentTable->deleteAttachment($ticket->id);
                        $this->ticketTable->delete($ticket->id);
                        $this->flashMessenger()->addSuccessMessage('Ticket excluido com sucesso.');
                        return $this->redirect()->toRoute('ticket');
                    } catch (\Exception $exception) {
                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                        return $this->redirect()->refresh();
                    }
                }
            }
            return new ViewModel([
                'ticket' => $ticket,
                'form' => $form->prepare()
            ]);
        } catch (\Exception $exception) {
            $this->flashMessenger()->addErrorMessage($exception->getMessage());
            return $this->redirect()->refresh();
        }
        return $this->redirect()->refresh();
    }

}
