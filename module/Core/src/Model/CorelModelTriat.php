<?php


namespace Core\Model;

use Laminas\Hydrator\Reflection;

trait CorelModelTriat
{
    public function exchangeArray(array $data)
    {
        (new Reflection())->hydrate($data, $this);
    }

    public function getArrayCopy()
    {
        return (new Reflection())->extract($this);
    }
}