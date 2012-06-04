<?php

/**
 * Description of Net_Sharedmemory_View_Helper_FacebookId
 *
 * @author david
 */
class Net_Sharedmemory_View_Helper_FacebookId
{
    public function FacebookId($userId)
    {
        $user = new Application_Model_User();
        $user->userId = $userId;
        $userMapper = new Application_Model_UserMapper();
        $userMapper->find($userId, $user);
        return $user->facebookId;
    }
}
