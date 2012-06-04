<?php

class UserController extends FacebookController
{
    public function init()
    {
        parent::init();
        $this->requireLogin();
    }

    public function indexAction()
    {
        $userMapper = new Application_Model_UserMapper();
        $user = new Application_Model_User();
        $userMapper->fetchByFacebookId($this->fbUserId, $user);
        $this->_forward("predictions", "user", null, array("userId" => $user->userId));
    }

    public function predictionsAction()
    {
        // Show a list of all the user's predictions (only past)
        $request = $this->getRequest();
        $userId = $request->getParam("userId");
        $this->view->userId = $userId;

        $raceMapper = new Application_Model_RaceMapper();
        $aRaces = $raceMapper->fetchAllBySeason(date("Y"));

        $aRacePredictions = array();

        $wins = array();
        
        // For each race of the season, get the user's predictions and the results.
        foreach ($aRaces as $race) {
            if ($race->qualifyingStart->isEarlier(new Zend_Date())) {
                $prediction = new Application_Model_Prediction();
                $predictionMapper = new Application_Model_PredictionMapper();

                // This could probably be refactored to be a bit nicer.
                if (!$predictionMapper->fetchByRaceAndUser($race->raceId, $userId, $prediction)) {
                    $prediction = null;
                }
                $aRacePredictions[$race->raceId] = array(
                    "race" => $race,
                    "prediction" => $prediction);
            } else {
                $aRacePredictions[$race->raceId] = array(
                    "race" => $race,
                    "prediction" => null);
            }

            $result = new Application_Model_Result();
            $resultMapper = new Application_Model_ResultMapper();
            if ($resultMapper->fetchByRaceId($race->raceId, $result)) {
                $aResults[$race->raceId] = $result;
                if ($prediction) {
                    $aPointsPerDriver[$race->raceId] = F1buzz_Standings_Calculator::calculatePointsPerDriver($result, $prediction);
                } else {
                    // The user hasn't made a prediction for this race so they
                    // get no points.
                    $aPointsPerDriver[$race->raceId] = array(
                        "pole" => 0,
                        "first" => 0,
                        "second" => 0,
                        "third" => 0,
                        "fastest" => 0,
                        "total" => 0);
                }
            } else {
                $aResults[$race->raceId] = null;
                $aPointsPerDriver[$race->raceId] = null;
            }

            

            // Trophies
            if (in_array($userId, $this->getWinners($race->raceId))) {
                $wins[$race->raceId] = $race;
            }
        }

        $this->view->aRacePredictions = $aRacePredictions;
        $this->view->aResults = $aResults;
        $this->view->aPointsPerDriver = $aPointsPerDriver;
        $this->view->wins = $wins;

        $chart = $this->getChart($aRacePredictions, $aResults);
        $this->view->chart = $chart;
    }

    /**
     * Returns the winners for a race.
     * @param int $raceId
     * @return array array of winning user IDs
     */
    private function getWinners($raceId)
    {
        $aWinnerIds = array();
        $topScore = 0;

        // Get the result
        $result = new Application_Model_Result();
        $resultMapper = new Application_Model_ResultMapper();
        if ($resultMapper->fetchByRaceId($raceId, $result)) {

            // Get each user's prediction.
            $userMapper = new Application_Model_UserMapper();
            $aUsers = $userMapper->fetchAll();

            $aUserPoints = array();

            // Build an array of "userId" => points
            // and record the top score.
            foreach ($aUsers as $user) {
                $prediction = new Application_Model_Prediction();
                $predictionMapper = new Application_Model_PredictionMapper();
                $predictionMapper->fetchByRaceAndUser($raceId, $user->userId, $prediction);
                $points = F1buzz_Standings_Calculator::calculatePoints($result, $prediction);
                if ($points > $topScore) {
                    $topScore = $points;
                }
                $aUserPoints[$user->userId] = $points;
            }

            foreach ($aUserPoints as $userId => $points) {
                if ($points >= $topScore) {
                    $aWinnerIds[] = $userId;
                }
            }
        }
        return $aWinnerIds;
    }

    private function getChart($aRacePredictions, $aResults)
    {
        require_once 'ofc/open-flash-chart.php';
        $title = new title("Points history");

        $bar = new bar();

        // Get the user's points for each race.
        $aPoints = array();
        $aLabels = array();
        foreach ($aRacePredictions as $raceId => $aRacePrediction) {
	
			// Make sure we have a result and a prediction
            if ($aResults[$raceId] && $aRacePrediction["prediction"]) {
                $aPoints[] = intval(F1buzz_Standings_Calculator::calculatePoints(
                        $aResults[$raceId], $aRacePrediction["prediction"]));
            } else {
                $aPoints[] = 0;
            }

            $aLabels[] = $aRacePrediction["race"]->location->shortName;
        }

        $bar->set_values($aPoints);

        $x = new x_axis();
        $x->set_colour( '#428C3E' );
        $x->steps(1);
        $x->set_labels_from_array($aLabels);

        $y = new y_axis();
        $y->set_range(0, 78, 5);

        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $chart->add_element( $bar );
        $chart->set_x_axis( $x );
        $chart->set_y_axis($y);
        $chart->set_bg_colour( '#FFFFFF' );

        return $chart;
    }
}