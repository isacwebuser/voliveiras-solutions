<?php


namespace User\Form;


use Laminas\Db\Adapter\Adapter;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use User\Form\Filter\UserFilter;

class UserForm extends Form
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct('user', []);

        $this->setInputFilter(new UserFilter($adapter));

        $this->setAttributes(['method' => 'POST']);

        $name = new Text('username');
        $name->setLabel('Username');
        $name->setAttributes([
            'placeholder' => 'Full name',
            'class' => 'form-control',
            'maxlength' => 120
        ]);
        $this->add($name);

        $desemail = new Email('email');
        $desemail->setLabel('E-mail');
        $desemail->setAttributes([
            'placeholder' => 'Input e-mail',
            'class' => 'form-control',
            'maxlength' => 255
        ]);
        $this->add($desemail);

        $country = new Select('country');
        $country->setLabel("Country");
        $country->setAttributes([
            'class' => 'form-control form-control-lg',
        ]);
        $country->setValueOptions([
            '0' => 'Country',
            '1' => 'Argentina',
            '2' => 'Brazil',
            '3' => 'Germany',
            '4' => 'United Kingdom',
            '5' => 'United States of America'
        ]);
        $this->add($country);


        $password = new Password('password');
        $password->setLabel("Password");
        $password->setAttributes([
            'placeholder' => 'Input password',
            'class' => 'form-control form-control-lg border-left-0',
            'maxlength' => 8
        ]);
        $this->add($password);

        $replypassword = new Password('replypassword');
        $replypassword->setLabel("Reply-password");
        $replypassword->setAttributes([
            'placeholder' => 'Input password',
            'class' => 'form-control form-control-lg border-left-0',
            'maxlength' => 8
        ]);
        $this->add($replypassword);

        $indTerm= new Checkbox('idcondition');
        $indTerm->setLabel('AI agree to all Terms & Conditions');
        $indTerm->setUseHiddenElement(true);
        $indTerm->setCheckedValue('yes');
        $indTerm->setUncheckedValue('no');

        $this->add($indTerm);

        $csrf = new Csrf('csrf');
        $csrf->setOptions([
            'csrf_options' => [
                'timeout' => 600
            ]
        ]);
        $this->add($csrf);
    }

}