<?php

/**
 * Description of LocationMapper
 *
 * @author david
 */
class Application_Model_LocationMapper
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
            $this->setDbTable('Application_Model_DbTable_Location');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Location $location)
    {
        $data = array(
            'location_id' => $location->getLocationId(),
            'name' => $location->getName(),
            'short_name' => $location->getShortName());

        if (null === ($id = $location->getLocationId())) {
            unset($data['location_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('location_id = ?' => $id));
        }
    }

    public function find($locationId, Application_Model_Location $location)
    {
        $result = $this->getDbTable()->find($locationId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $location->setLocationId($row->location_id)
            ->setName($row->name)
            ->setShortName($row->short_name);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Location();
            $entry->setLocationId($row->location_id)
                ->setName($row->name)
                ->setShortName($row->short_name);
            $entries[] = $entry;
        }
        return $entries;
    }
}