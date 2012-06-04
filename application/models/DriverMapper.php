<?php

/**
 * Description of DriverMapper
 *
 * @author david
 */
class Application_Model_DriverMapper
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
            $this->setDbTable('Application_Model_DbTable_Driver');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Driver $driver)
    {
        $data = array(
            'name' => $driver->getName(),
            'team' => $driver->getTeam(),
            'active' => $driver->getActive(),
            'helmet_image' => $driver->getHelmetImage());

        if (null === ($id = $driver->getDriverId())) {
            unset($data['driver_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('driver_id = ?' => $id));
        }
    }

    public function find($driverId, Application_Model_Driver $driver)
    {
        $result = $this->getDbTable()->find($driverId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $driver->setDriverId($row->driver_id)
            ->setName($row->name)
            ->setTeam($row->team)
            ->setActive($row->active)
            ->setHelmetImage($row->helmet_image);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Driver();
            $entry->setDriverId($row->driver_id)
                ->setName($row->name)
                ->setTeam($row->team)
                ->setActive($row->active)
                ->setHelmetImage($row->helmet_image);
            $entries[] = $entry;
        }
        return $entries;
    }
}