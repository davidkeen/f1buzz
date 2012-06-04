<?php

/**
 * Description of Calculator
 *
 * @author david
 */
class F1buzz_Standings_Calculator
{
    const POINTS_POLE = 10;
    const POINTS_FIRST = 25;
    const POINTS_SECOND = 18;
    const POINTS_THIRD = 15;
    const POINTS_FASTEST = 10;
    CONST POINTS_PODIUM = 8;

    // The number of results to include in the total score
    // Eg, if 16 then only count the top 16 results.
    const NUMBER_OF_RACES = 16;

    /**
     * Calculates the points for a prediction using the following rules:
     * Predict pole position: 5 points
     * Predict 1st: 10 points
     * Predict 2nd: 8 points
     * Predict 3rd: 6 points
     * Predict correct driver on podium but not in correct position: 3 points for each driver
     * Predict fastest lap: 5 points
     *
     * @param Application_Model_Race $result
     * @param Application_Model_Prediction $prediction
     * @return <type>
     */
    public static function calculatePoints(Application_Model_Result $result,
            Application_Model_Prediction $prediction)
    {
        $totalPoints = 0;
        
        if ($prediction->pole == $result->pole) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_POLE;
        }

        if ($prediction->first == $result->first) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_FIRST;
        } else if ($prediction->first == $result->second || $prediction->first == $result->third) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->second == $result->second) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_SECOND;
        } else if ($prediction->second == $result->first || $prediction->second == $result->third) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->third == $result->third) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_THIRD;
        } else if ($prediction->third == $result->second || $prediction->third == $result->first) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->fastest == $result->fastest) {
            $totalPoints += F1Buzz_Standings_Calculator::POINTS_FASTEST;
        }

        return $totalPoints;

    }

    public static function calculatePointsPerDriver(Application_Model_Result $result,
            Application_Model_Prediction $prediction)
    {
        $aPointsPerDriver = array(
            "pole" => 0,
            "first" => 0,
            "second" => 0,
            "third" => 0,
            "fastest" => 0);

        if ($prediction->pole == $result->pole) {
            $aPointsPerDriver["pole"] = F1Buzz_Standings_Calculator::POINTS_POLE;
        }

        if ($prediction->first == $result->first) {
            $aPointsPerDriver["first"] = F1Buzz_Standings_Calculator::POINTS_FIRST;
        } else if ($prediction->first == $result->second || $prediction->first == $result->third) {
            $aPointsPerDriver["first"] = F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->second == $result->second) {
            $aPointsPerDriver["second"] = F1Buzz_Standings_Calculator::POINTS_SECOND;
        } else if ($prediction->second == $result->first || $prediction->second == $result->third) {
            $aPointsPerDriver["second"] = F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->third == $result->third) {
            $aPointsPerDriver["third"] = F1Buzz_Standings_Calculator::POINTS_THIRD;
        } else if ($prediction->third == $result->second || $prediction->third == $result->first) {
            $aPointsPerDriver["third"] = F1Buzz_Standings_Calculator::POINTS_PODIUM;
        }

        if ($prediction->fastest == $result->fastest) {
            $aPointsPerDriver["fastest"] = F1Buzz_Standings_Calculator::POINTS_FASTEST;
        }

        $aPointsPerDriver["total"] = self::calculatePoints($result, $prediction);
        return $aPointsPerDriver;
    }

    /**
     *
     * @param array $aUserResults array("raceId" => points)
     */
    public static function getTotalPoints($aUserResults)
    {
        asort($aUserResults);
        $topResults = array_slice($aUserResults, -F1buzz_Standings_Calculator::NUMBER_OF_RACES, null, true);
        return array_sum($topResults);
    }
}
