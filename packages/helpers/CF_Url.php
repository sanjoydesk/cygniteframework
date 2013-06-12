<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2.5 or newer
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
 * @Sub Packages               :   Helper
 * @Filename                       :  Url
 * @Description                   :  This helper is used to take care of your url related stuffs
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class Url
{

     private static $url;
       /**
        * Header Redirect
        *
        * @access	public
        * @param	string	the URL
        * @param	string	the method: location or redirect
        * @return	string
        */
      public static function redirect_to($Uri = '', $type = 'location', $http_response_code = 302)
      {
            //CF_AppRegistry::load('Uri')->redirect($Uri = '', $type = 'location', $http_response_code = 302);
                     if ( ! preg_match('#^https?://#i', $uri))
                          $uri = self::site_url($uri);

                    switch($type):
                        case 'refresh' :
                                              header("Refresh:0;url=".$uri);
                                 break;
                        default:
                                             header("Location: ".$uri, TRUE, $http_response_code);
                                break;
                    endswitch;
                    exit;
      }
        /**
        * This Function is to get the previous visited url based on current url
        *
        * @access public
        * @return string
        */
        public function referer()
        {

        }

       /**
        * This Function is to get Uri Segment of the url
        *
        * @access public
        * @param  int
        * @return string
        */
        public static function segment($uri=1)
        {
                return Dispatcher::get_url_segment($uri);
        }

            /**
            * This Function is to get the set_basepath
            *
            * @access public
             *@param $url string
            * @return void
            */
           public static function set_basepath($url)
           {
                    if(is_null($url))
                           throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);

                      self::$url  = $url;
            }
           /**
            * This Function is to get the basepath
            *
            * @access public
            * @return string
            */
           public static function basepath()
           {
                 return  self::$url;
           }
           //@todo - Check index.php if available or not
           public static function sitepath($uri)
           {
                if($url !=""):
                     GHelper::trace();
                   $callee = debug_backtrace();
                   GHelper::display_errors(E_USER_ERROR, 'Unhandled Exception',"Cannot pass null argument to ".__METHOD__, $callee[1]['file'],$callee[1]['line'],TRUE);
                endif;

                return  self::$url.'index.php'.URIS.$uri;
           }


        /**
        * This Function is to encode the url
        *
        * @access public
        * @param  string
        * @return string
        */
        public static function encode()
        {

        }
         /**
        * This Function is to decode the url
        *
        * @access public
        * @param  string
        * @return string
        */
        public static function decode()
        {

        }
}