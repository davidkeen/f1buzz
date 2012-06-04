<?php

class IndexController extends FacebookController
{
    public function indexAction()
    {
         $this->_forward("standings", "standings");
    }

    /**
     * Called from Facebook Post-Authorise Callback URL.
     * Adds the user to the database.
     */
    public function authoriseAction()
    {
        // No output
        $this->_helper->viewRenderer->setNoRender(true);

        $user = $this->facebook->get_loggedin_user();
        if ($user != NULL && $this->facebook->fb_params['authorize'] == 1) {
            
            // Insert the user
            $user = new Application_Model_User();
            $user->facebookId = $this->facebook->fb_params["user"];
            $userMapper = new Application_Model_UserMapper();
            $userMapper->save($user);
            $this->logger->log("Added Facebook user: " . $user->facebookId, Zend_Log::INFO);
        }
    }

    /**
     * Called from Facebook Post-Remove Callback URL.
     * Removes the user and their predictions from the database.
     */
    public function removeAction()
    {
        // No output
        $this->_helper->viewRenderer->setNoRender(true);

        $user = $this->facebook->get_loggedin_user();
        if ($user != NULL && $this->facebook->fb_params['uninstall'] == 1) {

            //The user has removed your app
            $facebookId = $this->facebook->fb_params["user"];

            $user = new Application_Model_User();

            // Delete the user
            $userMapper = new Application_Model_UserMapper();
            $userMapper->fetchByFacebookId($facebookId, $user);

            $userMapper->delete($user);
            $this->logger->log("Deleted Facebook user: " . $facebookId, Zend_Log::INFO);

            // Delete the user's predictions
            $predictionMapper = new Application_Model_PredictionMapper();
            $predictionMapper->deleteByUserId($user->userId);
            $this->logger->log("Deleted predictions for Facebook user: " . $facebookId, Zend_Log::INFO);
        }
    }

}