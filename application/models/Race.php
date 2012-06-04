<?php

/**
 * Description of Application_Model_Race
 *
 * @author david
 */
class Application_Model_Race extends Application_Model_BaseModel
{
    protected $_raceId;
    protected $location;

    /** @var Zend_Date */
    protected $_qualifyingStart;

    /** @var Zend_Date */
    protected $_raceStart;

    public function setRaceId($raceId)
    {
        $this->_raceId = (int) $raceId;
        return $this;
    }

    public function getRaceId()
    {
        return $this->_raceId;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getQualifyingStart()
    {
        return $this->_qualifyingStart;
    }

    public function setQualifyingStart($startDateTime)
    {
        $this->_qualifyingStart = $startDateTime;
        return $this;
    }

    public function getRaceStart()
    {
        return $this->_raceStart;
    }

    public function setRaceStart($startDateTime)
    {
        $this->_raceStart = $startDateTime;
        return $this;
    }
}
