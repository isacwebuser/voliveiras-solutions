<?php


namespace Application\Model;

use Core\Model\CorelModelTriat;

class Ticket
{
    use CorelModelTriat;

    const LOW = 0;
    const MEDIUM = 1;
    const HIGH = 2;
    const DESASTER = 3;

    public $id;
    public $assunto;
    public $description;
    public $priority;
    public $created_at;
    public $user;

    public static function getPriorityDescription()
    {
        return [
            self::LOW =>'Baixo',
            self::MEDIUM =>'Médio',
            self::HIGH =>'Alta',
            self::DESASTER =>'Urgente'
        ];
    }

    public static function getPriority($priority)
    {
        switch ($priority) {
            case self::LOW:
                return 'Baixo';
                break;
            case self::MEDIUM:
                return 'Normal';
                break;
            case self::HIGH:
                return 'Alta';
                break;
            case self::DESASTER:
                return 'Urgente';
                break;
        }

    }
}