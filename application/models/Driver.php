<?php

/**
 * Description of Application_Model_Driver
 *
 * @author david
 */
class Application_Model_Driver extends Application_Model_BaseModel
{
    protected $_driverId;
    protected $_name;
    protected $_team;
    protected $_active;
    protected $_helmetImage;

    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setDriverId($driverId)
    {
        $this->_driverId = (int) $driverId;
        return $this;
    }

    public function getDriverId()
    {
        return $this->_driverId;
    }

    public function setTeam($team)
    {
        $this->_team = (string) $team;
        return $this;
    }

    public function getTeam()
    {
        return $this->_team;
    }

    public function setActive($active)
    {
        $this->_active = (bool) $active;
        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }

    public function setHelmetImage($helmetImage)
    {
        $this->_helmetImage = $helmetImage;
        return $this;
    }

    public function getHelmetImage()
    {
        return $this->_helmetImage;
    }
}
