<?php

/**
 * Description of Net_Sharedmemory_View_Helper_PredictionMade
 *
 * @author david
 */
class Net_Sharedmemory_View_Helper_PredictionMade
{
    public function PredictionMade($raceId, $userId)
    {
        $prediction = new Application_Model_Prediction();
        $predictionMapper = new Application_Model_PredictionMapper();
        return $predictionMapper->fetchByRaceAndUser($raceId, $userId, $prediction);
    }
}
