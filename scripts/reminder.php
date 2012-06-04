<?php
/* 
 * Checks if there is a race this weekend and if so posts a reminder news item.
 *
 * Should be run by cron once every Wednesday.
 *
 */

/**
 * Get the race for this weekend.
 *
 * @return Application_Model_Race the next race
 */
function raceWeekend()
{
    $sundayDayNumber = 0;
    $today = new Zend_Date();
    $dayNumber = $today->get(Zend_Date::WEEKDAY_DIGIT);
    $daysToAdd = ($sundayDayNumber - $dayNumber + 7) % 7;
    $nextSunday = $today->addDay($daysToAdd);

    $raceMapper = new Application_Model_RaceMapper();
    $aRace = $raceMapper->findByDate($nextSunday);
    if (count($aRace) == 1) {
        return $aRace[0];
    } else {
        return null;
    }
}

/**
 * Posts a news item.
 *
 * @param Application_Model_Race $race
 * @return string the ID of the posted item.
 */
function postReminder(Application_Model_Race $race)
{
    $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
    $facebook = new Facebook($config->facebook->apiKey, $config->facebook->apiSecret);
    $news = array(
        array(
            'message' => "There's a race this weekend (" . $race->Location->name . ")",
            'action_link' => array(
                'text' => 'Make your predictions.',
                'href' => $config->facebook->canvasUrl . "/prediction/prediction/raceId/" . $race->raceId)));
    return $facebook->api_client->dashboard_addGlobalNews($news);
	//return $facebook->api_client->dashboard_addNews($news, null, 603899601);
}

// Initialize the application path and autoloading
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/facebook'),
    get_include_path(),
)));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

require_once 'facebook.php';

// Define some CLI options
$getopt = new Zend_Console_Getopt(array(
    'env|e-s'    => 'Application environment (defaults to development)',
    'help|h'     => 'Help -- usage message',
));
try {
    $getopt->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    // Bad options passed: report usage
    echo $e->getUsageMessage();
    return false;
}

// If help requested, report usage message
if ($getopt->getOption('h')) {
    echo $getopt->getUsageMessage();
    return true;
}

// Initialize values based on presence or absence of CLI options
$env      = $getopt->getOption('e');
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (null === $env) ? 'development' : $env);

// Initialize Zend_Application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// Initialize and retrieve DB resource
$bootstrap = $application->getBootstrap();
$bootstrap->bootstrap('db');
$dbAdapter = $bootstrap->getResource('db');

date_default_timezone_set('UTC');

$race = raceWeekend();

if ($race) {
	$result = postReminder($race);
    if($result) {
        echo "Global news item posted.\n";
    } else {
        echo "Error posting global news item: " . print_r($result, true) . "\n";
		return false;
    }
}

return true;


