<?php

/**
 * Description of UserMapper
 *
 * @author david
 */
class Application_Model_UserMapper
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
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_User $user)
    {
        $data = array(
            'user_id' => $user->getUserId(),
            'facebook_id' => $user->getFacebookId());

        if (null === ($id = $user->getUserId())) {
            unset($data['user_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('user_id = ?' => $id));
        }
    }

    public function find($userId, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($userId);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setUserId($row->user_id)
            ->setFacebookId($row->facebook_id);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_User();
            $entry->setUserId($row->user_id)
                ->setFacebookId($row->facebook_id);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchByFacebookId($uid, Application_Model_User $user)
    {
        $select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $where = $select->where("facebook_id = ?", $uid);
        $row = $this->getDbTable()->fetchRow($where);
        if (!$row) {
            return;
        }
        $user->setUserId($row->user_id)
            ->setFacebookId($row->facebook_id);
    }

    public function delete($user)
    {
        $where = "user_id = {$user->userId}";
        $this->getDbTable()->delete($where);
    }

    public function deleteByFacebookId($facebookId)
    {
        $where = "facebook_id = {$facebookId}";
        $this->getDbTable()->delete($where);
    }
}