<?php

/**
 * Description of ResultMapper
 *
 * @author david
 */
class Application_Model_ResultMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Result');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Result $result)
    {
        $data = array(
            'result_id' => $result->getResultId(),
            'race_id' => $result->getRaceId(),
            'pole' => $result->getPole(),
            'first' => $result->getFirst(),
            'second' => $result->getSecond(),
            'third' => $result->getThird(),
            'fastest' => $result->getFastest());

        if (null === ($id = $result->getResultId())) {
            unset($data['result_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('result_id = ?' => $id));
        }
    }

    public function find($resultId, Application_Model_Result $result)
    {
        $result = $this->getDbTable()->find($resultId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $result->setResultId($row->result_id)
            ->setRaceId($row->race_id)
            ->setPole($row->pole)
            ->setFirst($row->first)
            ->setSecond($row->second)
            ->setThird($row->third)
            ->setFastest($row->fastest);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Result();
            $entry->setResultId($row->result_id)
                ->setRaceId($row->race_id)
                ->setPole($row->pole)
                ->setFirst($row->first)
                ->setSecond($row->second)
                ->setThird($row->third)
                ->setFastest($row->fastest);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchByRaceId($raceId, Application_Model_Result $result)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("race_id = ?", $raceId);
        $row = $this->getDbTable()->fetchRow($where);
        if (!$row) {
            return false;
        }
        $result->setResultId($row->result_id)
            ->setRaceId($row->race_id)
            ->setPole($row->pole)
            ->setFirst($row->first)
            ->setSecond($row->second)
            ->setThird($row->third)
            ->setFastest($row->fastest);
        return true;
    }
}