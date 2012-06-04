<?php

/**
 * Description of Application_Model_Prediction
 *
 * @author david
 */
class Application_Model_Prediction extends Application_Model_BaseModel
{
    protected $_predictionId;
    protected $_userId;
    protected $_raceId;
    protected $_pole;
    protected $_first;
    protected $_second;
    protected $_third;
    protected $_fastest;
    protected $_updated;

    public function getPredictionId()
    {
        return $this->_predictionId;
    }

    public function setPredictionId($predictionId)
    {
        $this->_predictionId = (int) $predictionId;
        return $this;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function setUserId($userId)
    {
        $this->_userId = $userId;
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

    public function getUpdated()
    {
        return $this->_updated;
    }

    public function setUpdated($updated)
    {
        $this->_updated = $updated;
        return $this;
    }
}
