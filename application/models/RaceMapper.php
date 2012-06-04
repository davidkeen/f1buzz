<?php

/**
 * Description of RaceMapper
 *
 * @author david
 */
class Application_Model_RaceMapper
{
    protected $_dbTable;
    protected $_locationMapper;

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
            $this->setDbTable('Application_Model_DbTable_Race');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Race $race)
    {
        $data = array(
            'race_id' => $race->getRaceId(),
            'location_id' => $race->getLocation()->getLocationId(),
            'qualifying_start' => $race->getQualifyingStart()->get(Zend_Date::ISO_8601),
            'race_start' => $race->getRaceStart()->get(Zend_Date::ISO_8601));

        if (null === ($id = $race->getRaceId())) {
            unset($data['race_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('race_id = ?' => $id));
        }
    }

    public function find($raceId, Application_Model_Race $race)
    {
        $result = $this->getDbTable()->find($raceId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

        if (!$this->_locationMapper) {
            $this->_locationMapper = new Application_Model_LocationMapper();
        }
        $location = new Application_Model_Location();
        $this->_locationMapper->find($row->location_id, $location);

        $race->setRaceId($row->race_id)
            ->setLocation($location)
            ->setQualifyingStart(new Zend_Date($row->qualifying_start, Zend_Date::ISO_8601))
            ->setRaceStart(new Zend_Date($row->race_start, Zend_Date::ISO_8601));
    }

    /**
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int $count  OPTIONAL An SQL LIMIT count.
     * @param int $offset OPTIONAL An SQL LIMIT offset.
     * @return array 
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
        $entries = array();
        foreach ($resultSet as $row) {

            if (!$this->_locationMapper) {
                $this->_locationMapper = new Application_Model_LocationMapper();
            }
            $location = new Application_Model_Location();
            $this->_locationMapper->find($row->location_id, $location);

            $entry = new Application_Model_Race();
            $entry->setRaceId($row->race_id)
                ->setLocation($location)
                ->setQualifyingStart(new Zend_Date($row->qualifying_start, Zend_Date::ISO_8601))
                ->setRaceStart(new Zend_Date($row->race_start, Zend_Date::ISO_8601));
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAllBySeason($year)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("YEAR(race_start) = ?", $year);
        $order = "race_start ASC";

        return $this->fetchAll($where, $order);
    }

    public function findByDate(Zend_Date $date)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("DATE(race_start) = ?", $date->toString('yyyy-MM-dd'));

        return $this->fetchAll($where);
    }
}