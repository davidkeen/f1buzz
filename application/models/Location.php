<?php

/**
 * Description of Location
 *
 * @author david
 */
class Application_Model_Location extends Application_Model_BaseModel
{
    protected $_locationId;
    protected $_name;
    protected $_shortName;

    public function setLocationId($locationId)
    {
        $this->_locationId = (int) $locationId;
        return $this;
    }

    public function getLocationId()
    {
        return $this->_locationId;
    }

    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setShortName($shortName)
    {
        $this->_shortName = (string) $shortName;
        return $this;
    }

    public function getShortName()
    {
        return $this->_shortName;
    }
}
