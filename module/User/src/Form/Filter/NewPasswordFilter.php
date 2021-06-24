<?php


namespace User\Form\Filter;

use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class NewPasswordFilter extends InputFilter
{
    public function __construct ()
    {
        $email = new Input('email');
        $email->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $email->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new StringLength(['max' => 255]));
        $this->add($email);

    }

}