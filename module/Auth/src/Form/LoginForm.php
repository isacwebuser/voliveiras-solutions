<?php


namespace Auth\Form;

use Auth\Form\Filter\LoginFilter;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Form;

class LoginForm extends Form
{

    public function __construct()
    {
        parent::__construct('login', []);

        $this->setInputFilter(new LoginFilter());
        $this->setAttributes(['method' => 'POST']);

        $desemail = new Email('email');
        $desemail->setLabel('E-mail');
        $desemail->setAttributes([
            'placeholder' => 'Input e-mail',
            'class' => 'form-control',
            'maxlength' => 255
        ]);
        $this->add($desemail);

        $password = new Password('password');
        $password->setLabel("Password");
        $password->setAttributes([
            'placeholder' => 'Input password',
            'class' => 'form-control form-control-lg border-left-0',
            'maxlength' => 8
        ]);
        $this->add($password);

        $csrf = new Csrf('csrf');
        $csrf->setOptions([
            'csrf_options' => [
                'timeout' => 600
            ]
        ]);
        $this->add($csrf);
    }
}