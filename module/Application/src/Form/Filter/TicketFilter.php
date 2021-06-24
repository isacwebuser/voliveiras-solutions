<?php


namespace Application\Form\Filter;

use Application\Model\Ticket;
use Laminas\Filter\File\RenameUpload;
use Laminas\InputFilter\FileInput;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\File\MimeType;
use Laminas\Validator\File\Size;
use Laminas\Validator\InArray;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class TicketFilter extends InputFilter
{
    public function __construct ()
    {

        $assunto = new Input('assunto');
        $assunto->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $assunto->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new StringLength(['max' => 100]));
        $this->add($assunto);

        $description = new Input('description');
        $description->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $description->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new StringLength(['max' => 500]));
        $this->add($description);

        $priority= new Input('priority');
        $priority->setRequired(true)
            ->getFilterChain()->attachByName('stringtrim')->attachByName('striptags');
        $priority->getValidatorChain()->addValidator(new NotEmpty())
            ->addValidator(new InArray([
                'haystack' => array_keys(Ticket::getPriorityDescription())
            ]));
        $this->add($priority);

        $attachment = new FileInput('attachment');
        $attachment->setRequired(false);
        $attachment->getValidatorChain()->addValidator(new Size(['max' => '10MB']));
        $attachment->getValidatorChain()->addValidator(new MimeType(['image/jpeg, image/png']));
        $attachment->getFilterChain()->attach(new RenameUpload([
            'target' => __DIR__ . '/../../../../../public/upload',
            'use_upload_name'      => false,
            'use_upload_extension' => true,
            'overwrite'            => true,
            'randomize'            => true,
        ]));
        $this->add($attachment);
    }

}