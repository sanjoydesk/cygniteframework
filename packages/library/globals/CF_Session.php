<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       :  CF_Session
 * @Description                   : This class is used to handle session mechanisam of the cygnite framework
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class Session extends Globals implements SecureData
{
        public $_var = "_SESSION";
        const SESSION_PREFIX = 'Cygnite';
        var $time_reference = 'time',$now,$config = array();
        public $_autostart= TRUE;
        private  $_initialized=FALSE,$_is_started = FALSE,$encrypt = NULL,$httponly = TRUE;
        private $sess_val = NULL;

        public function __construct()
       {
                    CF_AppRegistry::load_lib_class('CF_Encrypt',CF_ENCRYPT_KEY);
                    $this->config =  Config::get_config_items('config_items');
                    $this->initialize(CF_ENCRYPT_KEY);
                    $keys = array('HTTP_USER_AGENT', 'SERVER_PROTOCOL',
                                              'HTTP_ACCEPT_CHARSET', 'HTTP_ACCEPT_ENCODING', 'HTTP_ACCEPT_LANGUAGE');
                    $tmp = '';
                    foreach ($keys as $v) :
                            if (isset($_SERVER[$v])) $tmp .= $_SERVER[$v];
                    endforeach;

                     $browser_sig = md5($tmp);
                     if (empty($_SESSION)) : // new session
                            $_SESSION['log'] = md5($browser_sig);
                    elseif ($_SESSION['log'] != md5($browser_sig)):
                            session_destroy(); // destroy fake session
                            session_start(); // create a new “clean” session
                     endif;
                    if (!empty($_SERVER['HTTP_REFERER'])):
                        $url = parse_url($_SERVER['HTTP_REFERER']);

                        if ($url['host'] != $_SERVER['HTTP_HOST']):
                                session_destroy(); // destroy fake session
                        endif;

                    endif;
        }

                    public function set_gc_probability($value)
                    {
                            $value = (int)$value;
                                if($value>=0 && $value<=100):
                                        ini_set('session.gc_probability',$value);
                                        ini_set('session.gc_divisor','100');
                               endif;
                    }
                     public function initialize($secret = '')
                    {
                                $session_hash = 'sha512';
                               // Check if hash is available
                               if (in_array($session_hash, hash_algos()))
                                         ini_set('session.hash_function', $session_hash);// Set the has function.

                               // How many bits per character of the hash.
                               // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
                               ini_set('session.hash_bits_per_character', 5);
                               $this->set_gc_probability(1);
                               $this->use_cookie_();
                                if($this->_autostart)
                                        $this->start_session();
                                register_shutdown_function(array($this,'close_session'));

                    }

                    private function start_session()
                    {

                            if($this->_is_started ===FALSE){
                                    @session_set_save_handler(array($this,'open'),array($this,'close'),array($this,'read'),array($this,'write'),array($this,'destroy'),array($this,'gc_session'));
                            }
                            if(!$this->is_sess_started()):
                                    //Set the path for session

                                    $path =  str_replace('/', OS_PATH_SEPERATOR , APPPATH);

                                   if (is_dir($dir_path = APPPATH.'temp/sessions') === FALSE) :
                                            if (!mkdir($dir_path, 0777)) :
                                                    return;
                                            endif;
                                  endif;

                                    $this->set_session_save_path(WEB_ROOT.OS_PATH_SEPERATOR.$path.'temp'.OS_PATH_SEPERATOR.'sessions'.OS_PATH_SEPERATOR);
                                    $this->set_session_name($this->config['SESSION_CONFIG']['cf_session_name']);
                                   // $this->set_cookie_parameters($session_array);

                                    @session_start();

                                    if (!isset($_SESSION['initiated'])) :
                                                $this->regenarated_id(TRUE);
                                                $_SESSION['initiated'] = TRUE;
                                   endif;
                                    $this->_is_started = TRUE;
                                    $this->_initialized=TRUE;
                                    $fingerprint = 'SHIFLETT' . $_SERVER['HTTP_USER_AGENT'];
                                    $_SESSION['fingerprint'] = md5($fingerprint.$this->get_session_id());
                                    //$session_array = array($_SESSION['fingerprint'],$_SESSION['initiated'], $this->get_session_name());
                            endif;

                                if($this->get_session_id() ==''):
                                $message= 'Failed to start session.';
                                //trigger_error("Failed to start session.");
                                        if(function_exists('error_get_last')):
                                            $error=error_get_last();
                                            if(isset($error['message']))
                                                    $message=$error['message'];
                                        endif;
                                endif;

                    }

                                public function close_session()
                                {
                                        if($this->get_session_id() != "")
                                                @session_write_close();
                                        return TRUE;
                                }

                             /*Session fucntions need to be edit as per save handler    */

                              public function open($savePath,$sessionName)
	{
		return true;
	}

                            public function  read($sess_id)
                            {

                            }

                            public function write($id,$data)
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

                            public function gc_session($maxLifetime)
	{
		return true;
	}

                    /*Session fucntions need to be edit as per save handler  end  */


                    public function destroy_session($userdata)
                    {
                             if(is_string($userdata) && isset($_SESSION[$userdata])):
                                    unset($_SESSION[$userdata]);
                            endif;

                            if(is_array($userdata)):
                                    foreach($userdata as $key=>$val)
                                            unset($_SESSION[$key]);
                            endif;
                            /*
                            $this->clear_session();
                            if($this->get_session_id() != ""):
                                    @session_unset();
                                    @session_destroy();
                            endif;
                            */
                    }

                    private function clear_session()
                    {
                        foreach(array_keys($_SESSION) as $key)
	 unset($_SESSION[$key]);
                    }

                    public function get_session_id()
                    {
                        if($_SESSION['initiated'] === TRUE):
                                   return session_id();
                        endif;
                    }

                    public function regenarated_id($sessionid = FALSE)
                    {
                        session_regenerate_id($sessionid);
                    }


                    private function is_sess_started()
                    {
                        return ($this->get_session_id() !='') ? TRUE : FALSE;
                    }

                    private function set_session_name($name)
                    {
                            //ini_set('session.name','cf_session_value');
                            session_name($name);
                    }

                    public function get_session_name()
                    {
                            return session_name();
                    }

                    private function set_session_save_path($path)
                    {
                            if(is_dir($path) && is_writeable($path))
                                ini_set('session.save_path', $path);
                            else
                                trigger_error('Session save path error');
                    }

                    private function set_cookie_parameters($cookie_values)
                    {
                      // Make sure the session cookie is not accessable via javascript.
                        $cookie_params =session_get_cookie_params();
                            extract($cookie_params);
                            extract($cookie_values);
                            if(isset($this->httponly)):
                                    session_set_cookie_params($lifetime,$path,$domain,$secure,$this->httponly);
                            else:
                                    session_set_cookie_params($lifetime,$path,$domain,$secure);
                            endif;
                    }

                    public function get_cookie_parameters()
                    {
                            return session_get_cookie_params();
                    }

                    public function get_gc_probability()
                    {
                            return (int)ini_get('session.gc_probability');
                    }

                    private function get_session_count()
                    {
                            return count($_SESSION);
                    }

                    public function set_max_timeout($value)
                    {
                            ini_set('session.gc_maxlifetime',$value);
                    }

                    public function get_max_timeout()
                    {
                            return (int)ini_get('session.gc_maxlifetime');
                    }

                    // Force the session to only use cookies, not URL variables.
                    private function use_cookie_()
                    {
                               ini_set('session.use_only_cookies', 1);
                    }

                    public function count()
                    {
                            return $this->get_session_count();
                    }

                    /**
                    * Store a session variable
                    *
                    * @param string $name name of the session variable
                    * @param mixed $value value of the session; can be string, array, object, etc
                    */
                    public function setsession($key,$value)
                    {
                      /*
                          if(! $this->_is_started):
                                throw new ErrorException("Could not able to start session ".__FILE__);
                        endif;
                      */
                             $callee = debug_backtrace();
                                    switch ($value):
                                        case is_array($value):
                                                          $this->sess_val = $value;
                                                          $_SESSION[(string)$key]=  $this->sess_val;
                                                          return TRUE;
                                            break;
                                        case is_string($value):
                                                     if(!empty($key)):
                                                          $_SESSION[$key]= CF_AppRegistry::load('Encrypt')->encrypt($value);
                                                          return TRUE;
                                                    else:
                                                         GHelper::display_errors(E_USER_ERROR, 'Session Key', 'Empty key passed to '.__FUNCTION__.'()', $callee[0]['file'],$callee[0]['line'] , TRUE);
                                                    endif;
                                            break;
                                   endswitch;


                    }

                    /**
                    * Retrieve a session variable
                    *
                    * @param string $name Name of the variable you are looking for
                    * @return mixed
                    */
                    public function getsession($key,$defaultValue=NULL)
                    {
                                 switch ($key):
                                        case is_array($_SESSION[$key]):
                                                    return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
                                            break;
                                        case is_string($_SESSION[$key]):
                                                        return  isset($_SESSION[$key]) ? CF_AppRegistry::load('Encrypt')->decrypt($_SESSION[$key]) : $defaultValue;
                                            break;
                                   endswitch;


                          /*   if(!empty($this->sess_val)) :
                                    return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
                             else:
                                    return isset($_SESSION[$key]) ? CF_AppRegistry::load('Encrypt')->decrypt($_SESSION[$key]) : $defaultValue;
                            endif; */

                    }

                 public   function unset_session($userdata)
                    {
                            if(is_string($userdata)):
                                    //$userdata = array($userdata => '');
                                    unset($_SESSION[$userdata]);//unset(PHPSESSID);
                                    $_SESSION = array();
                            endif;

                            if(is_array($userdata)):
                                    foreach($userdata as $key=>$val):
                                            unset($_SESSION[$key]);
                                            $_SESSION = array();
                                    endforeach;
                            endif;

                            $_SESSION = array();

                            if( isset($_COOKIE[session_name()]) )
                                setcookie($this->get_session_name(), '', time() - 42000, '/');

                            session_destroy();
                            /*
                             if (isset($_COOKIE[session_name()])) {
                            $cookie_params = session_get_cookie_params();

                            setcookie(
                                session_name(),
                                false,
                                315554400, // strtotime('1980-01-01'),
                                $cookie_params['path'],
                                $cookie_params['domain'],
                                $cookie_params['secure']
                                );
                        }*/
                            return true;
                    }

                   public function _get_current_time()
                    {
                            if (strtolower($this->time_reference) == 'gmt') :
                                    $now = time();
                                    $time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
                            else :
                                    $time = time();
                            endif;
                            return $time;
                    }

                     public function __destruct()
                    {
                         session_write_close();
                    }
}