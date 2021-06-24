<?php


namespace Application\Form;

use Application\Form\Filter\TicketFilter;
use Application\Model\Ticket;
use Laminas\Db\Adapter\Adapter;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;


class TicketForm extends Form
{
    public function __construct(Adapter $adapter)
    {

        parent::__construct('ticket', []);

        $this->setInputFilter(new TicketFilter());

        $this->setAttributes([
            'method' => 'POST',
            'enctype'=> 'multipart/form-data'
        ]);

        $assunto = new Text('assunto');
        $assunto->setLabel('Assunto');
        $assunto->setAttributes([
            'placeholder' => 'Informe o assunto',
            'class' => 'form-control',
            'maxlength' => 100
        ]);

        $this->add($assunto);

        $priority = new Select('priority');
        $priority->setLabel("Prioridade",)
            ->setAttributes([
            'class' => 'form-control',
                ])
            ->setOptions([
                'value_options' => Ticket::getPriorityDescription()
            ]);
        $this->add($priority);

        $description = new Textarea('description');
        $description->setLabel('Descrição');
        $description->setAttributes([
            'class' => 'form-control',
            'rows' => 3,
            'spellcheck' => false,
            'data-gramm' => false
        ]);
        $this->add($description);

        $attachment = new File('attachment');
        $attachment->setLabel('Arquivos')
            ->setAttribute('multiple', 'multiple');
        $this->add($attachment);

        $csrf = new Csrf('csrf');
        $csrf->setOptions([
            'csrf_options' => [
                'timeout' => 600
            ]
        ]);
        $this->add($csrf);
    }

}