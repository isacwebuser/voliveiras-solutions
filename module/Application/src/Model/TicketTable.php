<?php


namespace Application\Model;

use Core\Model\AbstractCoreModelTable;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class TicketTable extends AbstractCoreModelTable
{
    public function findAll(array $params, $paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginateResults($params);
        }
        return $this->tableGatway->select($params);
    }

    private function fetchPaginateResults(array $params)
    {
        $select = new Select($this->tableGatway->getTable());
        $select->join(['u' => 'users'], 'u.id = user', [], Select::JOIN_LEFT )
            ->where($params);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Ticket());

        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGatway->getAdapter(),
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }
}