<?php


namespace User\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Form;
use User\Form\Filter\NewPasswordFilter;


class NewPassword extends Form
{
    public function __construct()
    {
        parent::__construct('new-password', []);

        $this->setInputFilter(new NewPasswordFilter());
        $this->setAttributes(['method' => 'POST']);

        $desemail = new Email('email');
        $desemail->setAttributes([
            'placeholder' => 'Input e-mail',
            'class' => 'form-control',
            'maxlength' => 255
        ]);
        $this->add($desemail);

        $csrf = new Csrf('csrf');
        $csrf->setOptions([
            'csrf' => [
                'timeout' => 600
            ]
        ]);
        $this->add($csrf);
    }
}