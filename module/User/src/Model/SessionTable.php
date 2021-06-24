<?php


namespace User\Model;


use Core\Model\AbstractCoreModelTable;

class SessionTable extends AbstractCoreModelTable
{
    public function save(array $data)
    {
        return parent::save($data);
    }
    public function checkEnvironment()
    {
        if (! extension_loaded('mysqli')) {
            throw new Exception\RuntimeException(
                'The Mysqli extension is required for this adapter but the extension is not loaded'
            );
        }
    }
}