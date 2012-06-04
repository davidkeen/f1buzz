<?php

class StandingsController extends FacebookController
{
    public function init()
    {
        parent::init();
        $this->requireLogin();
    }

    public function indexAction()
    {
         $this->_forward("standings");
    }

    public function standingsAction()
    {
       $raceMapper = new Application_Model_RaceMapper();

       // array of races for this year in date order.
       $aRaces = $raceMapper->fetchAllBySeason(date("Y"));

       // Rekey by race ID
       foreach ($aRaces as $race) {
           $racesById[$race->raceId] = $race;
       }
       $this->view->aRaces = $racesById;

       $aPoints = $this->getStandings($aRaces);
       $this->view->aStandings = $aPoints;

       
       
    }

    private function getStandings($aRaces)
    {
        $aStandings = array();

        // Build an array of [user][race] = "-";
        // for every user and every race.
        $userMapper = new Application_Model_UserMapper();
        $aUsers = $userMapper->fetchAll();
        foreach ($aUsers as $user) {
            foreach ($aRaces as $race) {
                $aStandings[$user->userId][$race->raceId] = "-";
            }
            
        }

        // Populate the array with user predictions
        $predictionMapper = new Application_Model_PredictionMapper();
        $predictions = $predictionMapper->fetchAllBySeason(date("Y"));

        foreach ($predictions as $prediction) {
            $result = new Application_Model_Result();
            $resultMapper = new Application_Model_ResultMapper();
            if ($resultMapper->fetchByRaceId($prediction->raceId, $result)) {

            // Get the points for each race.
                $aStandings[$prediction->userId][$result->raceId] =
                    F1buzz_Standings_Calculator::calculatePoints($result, $prediction);
            }
        }

        // Sort the user results by points.
        uasort($aStandings, array("StandingsController", "compareUserByPoints"));

        return $aStandings;
    }

    private static function compareUserByPoints($user, $otherUser)
    {
        $userTotal = F1buzz_Standings_Calculator::getTotalPoints($user);
        $otherUserTotal = F1buzz_Standings_Calculator::getTotalPoints($otherUser);
        if ($userTotal == $otherUserTotal) {
            return 0;
        }

        if ($userTotal > $otherUserTotal) {
            return -1;
        }

        if ($userTotal < $otherUserTotal) {
            return 1;
        }
    }
}