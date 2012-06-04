<?php

class InviteController extends FacebookController
{
    public function init()
    {
        parent::init();
        $this->requireLogin();
    }

    public function indexAction()
    {
        $this->_forward("invite");
    }

    public function inviteAction()
    {
        $this->view->content = $this->getInviteContent();
        $this->view->actionText = "Invite your friends to play";
        $this->view->excludeIds = $this->getInstalledFriends();
    }

    public function submitAction()
    {
        $invitedIds = $this->getRequest()->getParam("ids");
        if (!empty($invitedIds)) {

            // Output thanks message
            $numberOfFriends = count($invitedIds);
            if ($numberOfFriends > 1) {
                $message = " friends invited.";
            } else {
                $message = " friend invited.";
            }
            $this->_helper->FlashMessenger(array(
                'message' => $numberOfFriends . htmlentities($message),
                'level' => 'info'));
        }
        $this->_redirect("/invite");
    }

    /**
     * Gets a list of friends who already have the app installed.
     *
     * @return string comma separated list of friends
     */
    private function getInstalledFriends()
    {
        // Retrieve array of friends who've already authorized the app.
        $fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' . $this->fbUserId . ') AND is_app_user = 1';

        $result = $this->facebook->api_client->fql_query($fql);

        // Extract the user ID's returned in the FQL request into a new array.
        $friends = array();
        if (is_array($result) && count($result)) {
            foreach ($result as $friend) {
                $friends[] = $friend['uid'];
            }
        }

        // Convert the array of friends into a comma-delimeted string.
        return implode(',', $friends);
    }

    /**
     * Prepare the invitation text that all invited users will receive.
     *
     * @return string 
     */
    private function getInviteContent()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $content = htmlentities('<fb:name uid="' . $this->fbUserId . '" firstnameonly="true"></fb:name>' .
                ' is playing the <a href="' . $config->facebook->canvasUrl . '">F1 Buzz Prediction Game</a>' .
                ' and thought you might like to play!' . "\n" .
                ' <fb:req-choice url="' . $this->facebook->get_add_url() . '" label="Start playing"></fb:req-choice>');
        return $content;
    }
}
