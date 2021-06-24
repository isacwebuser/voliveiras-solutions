<?php


namespace Application\Model;


use Core\Model\CorelModelTriat;

class Attachment
{
    use CorelModelTriat;

    public $id;
    public $name;
    public $file;
    public $ticket;
}