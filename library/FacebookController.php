<?php

require_once 'facebook.php';

/**
 * @todo Externalise these config details
 */
abstract class FacebookController extends Zend_Controller_Action
{
    protected $simulateFb;
    protected $apiKey;
    protected $apiSecret;
    protected $canvasUrl;

    protected $fbUserId;

    /**
     * @var array The logged in user's timezone and locale.
     *            array('timezone' => timezone offset, 'locale' => locale)
     */
    protected $userTimezone;

    protected $logger;

    /**
     * Facebook api
     * @var Facebook
     */
    protected $facebook;

    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        
        $this->apiKey = $config->facebook->apiKey;
        $this->apiSecret = $config->facebook->apiSecret;
        $this->canvasUrl = $config->facebook->canvasUrl;

        $this->simulateFb = $config->facebook->simulate;
        if ($this->simulateFb) {
            Zend_Session::start();
            parent::init();
        } else {
            $this->facebook = new Facebook($this->apiKey, $this->apiSecret);
            //$session_key = md5($this->facebook->api_client->session_key);
            $session_key = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
            //file_put_contents('/var/tmp/debug.log', 'File: ' . __FILE__ . ' Line: ' . __LINE__ . " session_key: " . print_r($session_key, true) . "\n", FILE_APPEND);

            if(!Zend_Session::isStarted()) {
                //Zend_Session::setId($session_key);
                Zend_Session::start();
            }

            parent::init();
        }

        $this->logger = Zend_Registry::get('logger');
    }

    /**
     * This seems to cause problems.
     * Check: http://www.foobots.net/breakouts.html
     */
    protected function requireLogin()
    {
        if (!$this->simulateFb) {
            $this->fbUserId = $this->facebook->require_login();
        } else {
            $config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
            $this->fbUserId = $config->facebook->testFbUserId;
        }

        $this->view->fbUserId = $this->fbUserId;

        // Initialise the user locale data.
        $this->initTimezone();

    }

    protected function _redirect($url, array $options = array())
    {
        if(!$this->simulateFb) {
            $this->facebook->redirect($this->canvasUrl . $url);
        } else {
            parent::_redirect($url, $options);
        }
    }

    /**
     * Initialise the user timezone.
     * Note: when using the locale you should use Zend_Locale::findLocale to
     * protect against invalid locale strings.
     *
     * This should only be called after the user has logged in.
     *
     * @return array the user's timezone and locale
     *
     */
    private function initTimezone()
    {
        if ($this->simulateFb) {
            $userInfo = array(
                array("timezone" => 0, "locale" => "en_GB"));
//                array("timezone" => 1, "locale" => "pl_PL"));
        } else {
            $userInfo = $this->facebook->api_client->users_getInfo(
                $this->fbUserId, array('timezone', 'locale'));
        }

        $this->userTimezone = $userInfo[0];
    }
}

// Should we be just setting them here or should we add them to the response object?
header( 'Content-Type: text/html; charset=UTF-8' );
header('P3P: CP="CAO PSA OUR"');

