<?php


namespace User\Form\Filter;


use Laminas\Db\Adapter\Adapter;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Db\NoRecordExists;
use Laminas\Validator\Identical;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class UserFilter extends InputFilter
{
    public function __construct (Adapter $adapter)
    {
        $name = new Input('username');
        $name->setRequired(true)
             ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $name->getValidatorChain()->addValidator(new NotEmpty())
             ->addValidator(new StringLength(['max' => 120]));
        $this->add($name);

        $email = new Input('email');
        $email->setRequired(true)
             ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $email->getValidatorChain()->addValidator(new NotEmpty())
             ->addValidator(new StringLength(['max' => 255]))
             ->addValidator(new NoRecordExists([
                'table' => 'users',
                'field' => 'email',
                'adapter' => $adapter,
                'recordFound' => 'Este EMAIL já está associado a outro usuário'
            ]));
        $this->add($email);

        $password = new Input('password');
        $password->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $password->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new StringLength(['max' => 48, 'min' => 5]))
            ->addValidator(new Identical([
                'token' => 'replypassword',
                'message' => [
                    'notSame' => 'As senhas informadas não conferem.'
                ]
            ]));
        $this->add($password);

        $replypassword = new Input('replypassword');
        $replypassword->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $replypassword->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new StringLength(['max' => 48, 'min' => 5]));
        $this->add($replypassword);


    }

}