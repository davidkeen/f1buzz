<?php

/**
 * Description of PredictionMapper
 *
 * @author david
 */
class Application_Model_PredictionMapper
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
            $this->setDbTable('Application_Model_DbTable_Prediction');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Prediction $prediction)
    {
        if (!$this->isValid($prediction)) {
            throw new Exception("Error saving prediction");
        }
        
        $data = array(
            'user_id' => $prediction->getUserId(),
            'race_id' => $prediction->getRaceId(),
            'pole' => $prediction->getPole(),
            'first' => $prediction->getFirst(),
            'second' => $prediction->getSecond(),
            'third' => $prediction->getThird(),
            'fastest' => $prediction->getFastest(),
            'updated' => $prediction->getUpdated());

        if (null === ($id = $prediction->getPredictionId())) {
            unset($data['prediction_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('prediction_id = ?' => $id));
        }
    }

    public function find($predictionId, Application_Model_Prediction $prediction)
    {
        $result = $this->getDbTable()->find($predictionId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $prediction->setPredictionId($row->prediction_id)
            ->setUserId($row->user_id)
            ->setRaceId($row->race_id)
            ->setPole($row->pole)
            ->setFirst($row->first)
            ->setSecond($row->second)
            ->setThird($row->third)
            ->setFastest($row->fastest)
            ->setUpdated($row->updated);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Prediction();
            $entry->setPredictionId($row->prediction_id)
                ->setUserId($row->user_id)
                ->setRaceId($row->race_id)
                ->setPole($row->pole)
                ->setFirst($row->first)
                ->setSecond($row->second)
                ->setThird($row->third)
                ->setFastest($row->fastest)
                ->setUpdated($row->updated);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAllBySeason($year)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("YEAR(updated) = ?", $year);

        return $this->fetchAll($where);
    }

    public function fetchByRaceAndUser($raceId, $userId, Application_Model_Prediction $prediction)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("race_id = ?", $raceId)
            ->where("user_id = ?", $userId);
        $row = $this->getDbTable()->fetchRow($where);
        if (!$row) {
            return false;
        }
        $prediction->setPredictionId($row->prediction_id)
            ->setUserId($row->user_id)
            ->setRaceId($row->race_id)
            ->setPole($row->pole)
            ->setFirst($row->first)
            ->setSecond($row->second)
            ->setThird($row->third)
            ->setFastest($row->fastest)
            ->setUpdated($row->updated);
        return true;
    }

    public function deleteByUserId($userId)
    {
        $where = "user_id = {$userId}";
        $this->getDbTable()->delete($where);
    }

    /**
     * Validates the prediction.
     *
     * @param Application_Model_Prediction $prediction
     * @return boolean true if prediction is valid.
     */
    private function isValid($prediction)
    {
        $race = new Application_Model_Race();
        $raceMapper = new Application_Model_RaceMapper();
        $raceMapper->find($prediction->getRaceId(), $race);
        $now = new Zend_Date();

        // Check the prediction date is before deadline.
        return $race->qualifyingStart->isLater(new Zend_Date());
    }
}