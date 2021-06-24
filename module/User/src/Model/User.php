<?php


namespace User\Model;

use Core\Model\CorelModelTriat;

class User
{
    use CorelModelTriat;

    const ADM = 100;
    const INTERNO = 90;
    const CLIENTE = 10;

    public $id;
    public $username;
    public $email;
    public $country;
    public $idcondition;
    public $password;
    public $token;
    public $email_confirmed;

    /**
     * @return mixed
     */
    public static function getProfileDescription()
    {
        return ([
            self::ADM => 'Master',
            self::INTERNO => 'Funcionário',
            self::CLIENTE => 'Cliente'
        ]);
    }

    public static function getProfile($profile)
    {
        switch ($profile) {
            case self::ADM:
                return 'Master';
                break;
            case self::INTERNO:
                return 'Funcionário';
                break;
            case self::CLIENTE:
                return 'Cliente';
                break;
        }
    }



}