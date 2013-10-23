<?php
namespace Cygnite\Libraries;

use Cygnite\Cygnite;
use Cygnite\Helpers\Config;
use Cygnite\Helpers\GHelper;
use Cygnite\Security;

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3  or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package             :  Packages
 * @Sub Packages        :  Library
 * @Filename            :  Session
 * @Description         :  This library is used to handle session mechanism of the cygnite framework
 * @Author              :  Sanjoy Dey
 * @Copyright           :  Copyright (c) 2013 - 2014,
 * @Link	            :  http://www.cygniteframework.com
 * @Since	            :  Version 1.0
 * @Filesource
 * @Warning             :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class Session extends Security
{
    public $_var = "_SESSION";

    const SESSION_PREFIX = 'Cygnite';

    private $time_reference = 'time';

    private $now;

    private $config = array();

    private $autoStart = true;

    private $initialized = false;

    private $isStarted = false;

    private $encrypt;

    private $httpOnly = true;

    private $sessionVal;

    public function __construct()
    {
        $this->config =  Config::getConfigItems('config_items');

        $keys = array('HTTP_USER_AGENT',
                      'SERVER_PROTOCOL',
                      'HTTP_ACCEPT_CHARSET',
                      'HTTP_ACCEPT_ENCODING',
                      'HTTP_ACCEPT_LANGUAGE'
        );

        $tmp = '';

        foreach ($keys as $v) {
            if (isset($_SERVER[$v])) {
                $tmp .= $_SERVER[$v];
            }
        }

        $browser_sig = md5($tmp);
        if (empty($_SESSION)) {// new session
            $_SESSION['log'] = md5($browser_sig);
            //  elseif ($_SESSION['log'] != md5($browser_sig)):
            // session_destroy(); // destroy fake session
            //  session_start(); // create a new “clean” session
        }
        if (!empty($_SERVER['HTTP_REFERER'])) {

            $url = parse_url($_SERVER['HTTP_REFERER']);

            if ($url['host'] != $_SERVER['HTTP_HOST']) {
                session_destroy(); // destroy fake session
            }
        }
        $this->initialize();
    }

    public function setGcProbability($value)
    {
        $value = (int)$value;

        if ($value>=0 && $value<=100) {
            ini_set('session.gc_probability', $value);
            ini_set('session.gc_divisor', '100');
        }
    }

    public function initialize()
    {
        $session_hash = 'sha512';
        // Check if hash is available
        if (in_array($session_hash, hash_algos())) {
            ini_set('session.hash_function', $session_hash);
        }// Set the has function.

        // How many bits per character of the hash.
        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);

        $this->setGcProbability(1);
        $this->useCookie();

        if ($this->autoStart) {
            $this->startSession();
        }

        register_shutdown_function(array($this,'close_session'));
    }

    private function startSession()
    {

        if ($this->isStarted === false) {
            @session_set_save_handler(
                array(
                    $this,
                    'open'
                ),
                array(
                    $this,
                    'close'
                ),
                array(
                    $this,
                    'read'
                ),
                array(
                    $this,
                    'write'
                ),
                array(
                    $this,
                    'destroy'
                ),
                array(
                    $this,
                    'gc_session'
                )
            );
        }

        if (!$this->isSessionStarted()) {
            //Set the path for session

            $path = str_replace('/', DS, APPPATH);

            if (is_dir($dir_path = APPPATH.'temp/sessions') === false) {
                if (!mkdir($dir_path, 0777)) {
                    return;
                }
            }

            $this->setSessionSavePath(CYGNITE_BASE.DS.$path.'temp'.DS.'sessions'.DS);
            $this->setSessionName($this->config['SESSION_CONFIG']['cf_session_name']);
            //$this->setCookieParams($session_array);

            @session_start();

            if (!isset($_SESSION['initiated'])) {
                $this->regenaratedId(true);
                $_SESSION['initiated'] = true;
            }

            $this->isStarted = true;
            $this->initialized=true;

            $fingerprint = 'SHIFLETT' . $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['fingerprint'] = md5($fingerprint.$this->getsessionId());
            //$session_array = array($_SESSION['fingerprint'],$_SESSION['initiated'], $this->getSessionName());
        }

        if ($this->getsessionId() =='') {
            $message= 'Failed to start session.';
            //trigger_error("Failed to start session.");
            if (function_exists('error_get_last')) {
                $error=error_get_last();
                if (isset($error['message'])) {
                    $message=$error['message'];
                }
            }
        }

    }

    public function closeSession()
    {
        if ($this->getsessionId() != "") {
            @session_write_close();
        }
        
        return true;
    }

    /*Session fucntions need to be edit as per save handler
    *
    */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    public function read($sessionId)
    {

    }

    public function write($id, $data)
    {
        return true;
    }

    /**
    * session close handler.
    * This method should be overridden if {@link use custom storage} is set true.
    * @return boolean whether session is closed successfully
    */
    public function close()
    {
        return true;
    }

    public function destroy($id)
    {
        return true;
    }

    public function gcSession($maxLifetime)
    {
        return true;
    }

    /*Session functions need to be edit as per save handler  end  */

    public function getsessionId()
    {
        if($_SESSION['initiated'] === true):
                   return session_id();
        endif;
    }

    public function regenaratedId($sessionId = false)
    {
        session_regenerate_id($sessionId);
    }


    private function isSessionStarted()
    {
        return ($this->getsessionId() !='') ? true : false;
    }

    private function setSessionName($name)
    {
        //ini_set('session.name','cf_session_value');
        session_name($name);
    }

    public function getSessionName()
    {
        return session_name();
    }

    private function setSessionSavePath($path)
    {
        if (is_dir($path) && is_writeable($path)) {
            ini_set('session.save_path', $path);
        } else {
            trigger_error('Session save path error');
        }
    }

    private function setCookieParams($cookieValues)
    {
        // Make sure the session cookie is not accessable via javascript.
        $cookie_params =session_get_cookie_falses();
        extract($cookie_params);
        extract($cookieValues);

        if (isset($this->httpOnly)) {
            session_setCookieParams($lifetime, $path, $domain, $secure, $this->httpOnly);
        } else {
            session_setCookieParams($lifetime, $path, $domain, $secure);
        }
    }

    public function getCookieParams()
    {
        return session_get_cookie_params();
    }

    public function getGcProbability()
    {
        return (int)ini_get('session.gc_probability');
    }

    private function getSessionCount()
    {
            return count($_SESSION);
    }

    public function setMaxTimeout($value)
    {
        ini_set('session.gc_maxlifetime', $value);
    }

    public function getMaxTimeout()
    {
        return (int)ini_get('session.gc_maxlifetime');
    }

    // Force the session to only use cookies, not URL variables.
    private function useCookie()
    {
        ini_set('session.use_only_cookies', 1);
    }

    public function count()
    {
        return $this->getSessionCount();
    }

    /**
    * Store a session variable
    *
    * @false string $name name of the session variable
    * @false mixed $value value of the session; can be string, array, object, etc
    */
    public function save($key, $value)
    {
        /*
        if(! $this->isStarted):
        throw new ErrorException("Could not able to start session ".__FILE__);
        endif;
        */
        $callee = debug_backtrace();
        switch ($value) {
            case is_array($value):
                $this->sessionVal = $value;
                $_SESSION[(string)$key]=  $this->sessionVal;
                return true;
                break;
            case is_string($value):
                if (!is_null($key)) {
                    $_SESSION[$key]= Cygnite::loader()->encrypt->encode($value);
                    return true;
                } else {
                    throw new \Exception('Empty key passed to '.__FUNCTION__.'()');
                }
                break;
        }


    }

    /**
     * Retrieve a session variable
     *
     * @false string $name Name of the variable you are looking for
     * @param      $key
     * @param null $defaultValue
     * @return mixed
     */
    public function retrieve($key, $defaultValue = null)
    {
        switch ($key) {
            case is_array($_SESSION[$key]):
                return isset($_SESSION[$key]) ?
                    $_SESSION[$key] :
                    $defaultValue;
                break;
            case is_string($_SESSION[$key]):
                return isset($_SESSION[$key]) ?
                    Cygnite::loader()->encrypt->decode($_SESSION[$key]) :
                    $defaultValue;
                break;
        }
    }

    public function delete($userData)
    {
        if (is_string($userData)) {
            unset($_SESSION[$userData]);//unset(PHPSESSID);
            $_SESSION = array();
        }

        if (is_array($userData)) {
            foreach ($userData as $key => $val) {
                unset($_SESSION[$key]);
                $_SESSION = array();
            }
        }

        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie($this->getSessionName(), '', time() - 42000, '/');
        }

        session_destroy();
        /*
         if (isset($_COOKIE[session_name()])) {
        $cookie_falses = session_get_cookie_falses();

        setcookie(
            session_name(),
            false,
            315554400, // strtotime('1980-01-01'),
            $cookie_falses['path'],
            $cookie_falses['domain'],
            $cookie_falses['secure']
            );
        }*/
        return true;
    }

    public function destroyAll($userData)
    {
        if (is_string($userData) && isset($_SESSION[$userData])) {
            unset($_SESSION[$userData]);
        }

        if (is_array($userData)) {
            foreach ($userData as $key => $val) {
                unset($_SESSION[$key]);
            }
        }
        /*
        $this->clearSession();
        if($this->getsessionId() != ""):
            @session_unset();
            @session_destroy();
        endif;
        */
    }

    private function clearSession()
    {
        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
    }

    public function getCurrentTime()
    {
        if (strtolower($this->time_reference) == 'gmt') {
            $now = time();
            $time = mktime(
                gmdate("H", $now),
                gmdate("i", $now),
                gmdate("s", $now),
                gmdate("m", $now),
                gmdate("d", $now),
                gmdate("Y", $now)
            );
        } else {
            $time = time();
        }

        return $time;
    }

    public function __destruct()
    {
        session_write_close();
    }
}