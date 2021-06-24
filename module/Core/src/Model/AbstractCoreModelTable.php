<?php


namespace Core\Model;



use Laminas\Db\Exception\RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

abstract class AbstractCoreModelTable
{
    protected $tableGatway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGatway = $tableGateway;
    }

    public function getBy(array $params)
    {
        $rowset = $this->tableGatway->select($params);
        $row = $rowset->current();

        if (!$row) {
            return null;
        }

        return $row;
    }

    public function save(array $data)
    {
        unset(
            $data['csrf'],
            $data['replypassword'],
            $data['attachment'],
        );

        if (isset($data['id']))
        {
            $id = (int) $data['id'];

            if (! $this->getBy(['id' => $id])){
                throw new RuntimeException(sprintf(
                    'Connot update indentifier %d; does not exist',
                    $id
                ));
            }
            $this->tableGatway->update($data, ['id' => (int) $id]);

            return $this->getBy(['id' => $id]);
        }
        $this->tableGatway->insert($data);

        return $this->getBy(['id' => $this->tableGatway->getLastInsertValue()]);
    }

    public function delete($id)
    {
        $this->tableGatway->delete(['id' => (int) $id]);
    }



}