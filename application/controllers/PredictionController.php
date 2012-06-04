<?php

class PredictionController extends FacebookController
{
    public function init()
    {
        parent::init();
        $this->requireLogin();

    }

    public function indexAction()
    {
        $this->_forward('calendar');
    }

    /**
     * Shows a list of races for this season.
     */
    public function calendarAction()
    {
        $raceMapper = new Application_Model_RaceMapper();
        $aRaces = $raceMapper->fetchAllBySeason(date("Y"));
        $this->view->aRaces = $aRaces;
        $this->view->nextRaceKey = $this->getNextRaceKey($aRaces);
        $this->view->userId = $this->getUserId($this->fbUserId);
        $this->view->userTimezone = $this->userTimezone;
    }

    /**
     * Makes the prediction
     */
    public function predictionAction()
    {
        $request = $this->getRequest();
        $raceId = $request->getParam("raceId");

        // Fetch the race details
        $race = new Application_Model_Race();
        $raceMapper = new Application_Model_RaceMapper();
        $raceMapper->find($raceId, $race);

        // Fetch the prediction
        $prediction = new Application_Model_Prediction();
        $predictionMapper = new Application_Model_PredictionMapper();
        $predictionMapper->fetchByRaceAndUser(
            $raceId, $this->getUserId($this->fbUserId), $prediction);

        // If it is new then set the race and user ID.
        if (!$prediction->predictionId) {
            $prediction->raceId = $raceId;
            $prediction->userId = $this->getUserId($this->fbUserId);
        }

        // We now have a prediction object with the race and user id set
        $form = new Application_Form_Prediction($prediction);

        // Disable the form if it is passed the deadline.
        if ($race->qualifyingStart->isEarlier(new Zend_Date())) {
            foreach ($form->getElements() as $element) {
                $element->disable = true;
            }
            $this->_helper->FlashMessenger(array(
                'message' => '<fb:intl>The deadline for predictions for this race has passed.</fb:intl><br />
                    <fb:intl>You must make your predictions before the start of qualifying.</fb:intl>',
                'level' => 'info'));
        }

        if ($request->isPost()) {

            // Submitting
            if ($form->isValid($request->getPost())) {
                $prediction = new Application_Model_Prediction($form->getValues());
                $prediction->userId = $this->getUserId($this->fbUserId);
                $mapper = new Application_Model_PredictionMapper();
                
                try {
                    $mapper->save($prediction);

                    $this->_helper->FlashMessenger(array(
                        'message' => 'Prediction saved. Good luck!',
                        'level' => 'info'));

                    $this->view->feedStory = $this->getFeedStoryJs($prediction);
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger(array(
                        'message' => $e->getMessage(),
                        'level' => 'error'));
                }
            }
        } 

        $this->view->race = $race;
        $this->view->form = $form;
        $this->view->userTimezone = $this->userTimezone;
    }

    /**
     * Gets the userId from facebook ID.
     *
     * TODO: should probably be in some kind of helper class?
     *
     * @param <type> $facebookId
     * @return <type>
     */
    private function getUserId($facebookId)
    {
        $user = new Application_Model_User();
        $userMapper = new Application_Model_UserMapper();
        $userMapper->fetchByFacebookId($facebookId, $user);
        return $user->getUserId();
    }

    private function getFeedStoryJs(Application_Model_Prediction $prediction)
    {
        // Get the race name
        $race = new Application_Model_Race();
        $raceMapper = new Application_Model_RaceMapper();
        $raceMapper->find($prediction->raceId, $race);
        $locationName = $race->location->name;

        // Get the predicted winning driver
        $driver = new Application_Model_Driver();
        $driverMapper = new Application_Model_DriverMapper();
        $driverMapper->find($prediction->first, $driver);
        $driverName = $driver->name;

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

        $aAttachment = array(
            "name" => "F1 Buzz",
            "href" => $config->facebook->canvasUrl,
            "caption" => "{*actor*} picked {$driverName} to win the {$locationName} Grand Prix.",
//            "description" => "Descriptive text about the story. This field can contain plain text only and should be no longer than is necessary for a reader to understand the story",
            "media" => array(array("type" => "image", "src" => $config->app->baseUrl . "/assets/images/helmets/" . $driver->helmetImage, "href" => "http://www.f1buzz.net"))
            );
        $jsonAttachment = json_encode($aAttachment);

        
        $output = 'FB_RequireFeatures(["Connect"], function(){' .
            'FB.Facebook.init("' . $config->facebook->apiKey . '", "/xd_receiver.html");' .
            'FB.Connect.get_status().waitUntilReady(function(status) {' .
            'FB.Connect.streamPublish("", ' . $jsonAttachment . ');' .
            '});' .
            '});';
        return $output;
    }

    /**
     * Returns the array key of the next race available for prediction.
     *
     * @param array $aRaces array of Race objects sorted by date.
     * @return int the key of the next race.
     */
    private function getNextRaceKey($aRaces)
    {
        $now = new Zend_Date();

        foreach ($aRaces as $key => $race) {
            if ($race->qualifyingStart->isLater($now)) {
                return $key;
            }
        }

        return 0;
    }
}