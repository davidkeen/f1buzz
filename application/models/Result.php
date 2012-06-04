<?php

/**
 * Description of Application_Model_Result
 *
 * @author david
 */
class Application_Model_Result extends Application_Model_BaseModel
{
    protected $_resultId;
    protected $_raceId;
    protected $_pole;
    protected $_first;
    protected $_second;
    protected $_third;
    protected $_fastest;

    public function getResultId()
    {
        return $this->_resultId;
    }

    public function setResultId($resultId)
    {
        $this->_resultId = (int) $resultId;
        return $this;
    }

    public function getRaceId()
    {
        return $this->_raceId;
    }

    public function setRaceId($raceId)
    {
        $this->_raceId = $raceId;
        return $this;
    }

    public function getPole()
    {
        return $this->_pole;
    }

    public function setPole($pole)
    {
        $this->_pole = $pole;
        return $this;
    }

    public function getFirst()
    {
        return $this->_first;
    }

    public function setFirst($first)
    {
        $this->_first = $first;
        return $this;
    }

    public function getSecond()
    {
        return $this->_second;
    }

    public function setSecond($second)
    {
        $this->_second = $second;
        return $this;
    }

    public function getThird()
    {
        return $this->_third;
    }

    public function setThird($third)
    {
        $this->_third = $third;
        return $this;
    }

    public function getFastest()
    {
        return $this->_fastest;
    }

    public function setFastest($fastest)
    {
        $this->_fastest = $fastest;
        return $this;
    }
}
