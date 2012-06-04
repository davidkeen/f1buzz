<?php

/**
 * Description of Application_Model_DbTable_User
 *
 * @author david
 */
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    /* Table name */
    protected $_name = "user";

    /* Primary key */
    protected $_primary = "user_id";
}
