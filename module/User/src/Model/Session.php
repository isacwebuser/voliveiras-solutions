<?php


namespace User\Model;


use Core\Model\CorelModelTriat;

class Session
{
    use CorelModelTriat;

    public $idsession;
    public $name;
    public $modified;
    public $lifetime;
    public $data;
    public $iduser;

}