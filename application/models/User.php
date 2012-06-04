<?php

/**
 * Description of Application_Model_User
 *
 * @author david
 */
class Application_Model_User extends Application_Model_BaseModel
{
    protected $_userId;
    protected $_facebookId;

    public function setUserId($userId)
    {
        $this->_userId = (int) $userId;
        return $this;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function setFacebookId($facebookId)
    {
        $this->_facebookId = (string) $facebookId;
        return $this;
    }

    public function getFacebookId()
    {
        return $this->_facebookId;
    }
}
