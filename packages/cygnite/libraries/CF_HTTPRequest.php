<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
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
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : CF_HTTPRequest
 * @Description                   : This library used to handle all http requests
 * @Author                          :  Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class CF_HTTPRequest
{
         /**
           *
           * @var string
           */
            private $_param; // the returned POST/GET values
            private $_cookie; // the returned COOKIE values


            function __construct() { }


            /**
             * Set new value to given GET variable.
             * @param string $variable Get variable name.
             * @param mixed $value     New value to be set.
             */
         /* To GET library
          *
          *    public static function set_get_value( $variable, $value="")
            {
                  $_GET[$variable] = $value;
                  if(is_array($variable)):
                       foreach($variable as $key=>$val):
                                $_GET[$key] = $this->clean_variables($val);
                       endforeach;
                endif;
            }


            public function  get($key, $default =NULL)
            {
                    $this->_param = ( isset($_GET[$key]) ? $_GET[$key] : $default );
                    //$this->_clean($value);
                    return $this->clean_variables($this->_param);
            }

            */

            /* To Post Library
             *    public function post_values($key = NULL, $default = NULL)
                {
                    if (NULL === $key)
                          return $this->clean_variables($_POST);
                    else
                        return (isset($_POST[$key])) ? $this->clean_variables($_POST[$key]) : $default;
                }

             /**
             * Sets new value for given POST variable.
             * @param string $variable Post variable name
             * @param mixed $value     New value to be set.
             */
       /*    public static function set_post_value( $variable, $value="")
            {
                $_POST[$variable] = $value;
                if(is_array($variable)):
                       foreach($variable as $key=>$val):
                                $_POST[$key] = $this->clean_variables($val);
                       endforeach;
                endif;
            }

            public function is_submited($input)
            {
                    return  filter_has_var(INPUT_POST, $input) ? TRUE : FALSE;
            } */
    /*    Added in Request Library
            public function has_request($key, $default = NULL)
            {
                    if( isset($_POST[$key]) ):
                            $this->_param = $_POST[$key];
                    else:
                            if( isset($_GET[$key]) )
                                $this->_param = $_GET[$key];
                            else
                                $this->_param = $defvalue;
                    endif;
                    if( empty($this->_param) ) $this->_param = $default;
               return $this->clean_variables($this->_param);
            } */

        /*    public static function get_query()
            {
                    $getResult = array();
                    foreach($_GET as $key => $val)
                            $getResult[] = $key ."=". $val;
                    return implode("&", $getResult);
            }  */

            private function _clean($value, $isencoded=FALSE)
            {
                    return ($isencoded) ? strip_tags(trim(urldecode($value))) : strip_tags(trim($value));
            }

            private static function clean_variables($value)
            {
                        if( empty($value) ) return $value;
                        if( is_null($value) ) return $value;
                        if( is_string($value) ):
                                $spec = array('/[\']/', '/--/', '/\bdrop\b/i', '/\bdelete\b/i', '/\binsert\b/i', '/\bupdate\b/i');
                                $value = preg_replace($spec, "", $value);
                                return $value;
                        endif;
                    return $value;
            }

             /**
             * Sets or returns the cookie variable value.
             *
             * @param string $name The cookie name.
             * @param mixed $value If given, the cookie variable will assume this value.
             *                     Otherwise, the function returns current cookie variable value.
             * @param int $expire  Optional excfration time, in minutes. Defaults to 24 hours (60 * 24 = 1440).
             * @return mixed O valor do cookie.
             */
 /* Added in Cookie Library           public static function set_cookie( $name, $value = NULL, $expire = 1440 )
            {
                // No given value, the user is requesting the cookie value.
                if( is_null($value) ):
                    $this->_cookie = ( (isset($_COOKIE[$name]) ) ?   $_COOKIE[$name] :  NULL);
                    return $this->_cookie;
                endif;
                $expire = time() + ($expire * 60); // Excfre will passed in minutes, so multiply by 60 seconds.
                setcookie($name, $value, $expire);  // Define Cookie.
            }
*/
            /**
            * Private method to sanitize data
            * @param mixed $data
            */
      /* Added in HTML helper class
            private function san_data($value)
            {
                    switch($this->strength):
                                    default:
                                                  return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
                                            break;
                                    case 'strong':
                                                 return htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                                            break;
                                    case 'strict':
                                                 return urlencode($value);
                                            break;
                    endswitch;
            } */

            /**
             * Deletes a cookie.
             *
             * @param string $cookie Cookie name to be deleted.
             */
     /* To Cookie Library       public  function delete_cookie( $cookie )
            {
                  setcookie($cookie, FALSE, time() - 3600);
            }  */
            public function __destruct()
            {
                    unset($this->_cookie);
                    unset($this->_param);
                    unset($this);
            }
}