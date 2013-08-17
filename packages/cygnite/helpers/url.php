<?php
namespace Cygnite\Helpers;

if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
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
 * @Sub Packages               :   Helper
 * @Filename                       :  Url
 * @Description                   :  This helper is used to take care of your url related stuffs
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
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
      public static function redirect_to($uri = '', $type = 'location', $http_response_code = 302)
      {
          $uri = str_replace(array('.','/'),'/',$uri);
           
          if (! preg_match('#^https?://#i', $uri))
                $uri = self::sitepath($uri);
          if($type == 'refresh')
                  header("Refresh:0;url=".$uri);
          else
                  header("Location: ".$uri, TRUE, $http_response_code);
      }
        /**
        * This Function is to get the previous visited url based on current url
        *
        * @access public
        * @return string
        */
        public function referedfrom()
        {

        }

       /**
        * This Function is to get Uri Segment of the url
        *
        * @access public
        * @param  int
        * @return string
        */
        public static function segment($segment=1)
        {
                    // Current Request URI
                    $uri = $_SERVER['REQUEST_URI'];

                    // Remove rewrite basepath (= allows one to run the router in a subfolder)
                    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
                    $uri = substr($uri, strlen($basepath));

                    // Don't take query params into account on the URL
                    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));

                    // Remove trailing slash + enforce a slash at the start
                     $uri = '/' . trim($uri, '/');

                   $urlArray = array_filter(explode('/', $uri));

                   $indexCount = array_search('index.php',$urlArray);

                   if($indexCount==TRUE)
                          return @$urlArray[$indexCount+$segment];
                   else
                          return @$urlArray[$segment];

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
            * This Function is to get the application basepath without index.php
            *
            * @access public
            * @return string
            */
           public static function basepath()
           {
                 return  self::$url;
           }

          /**
            * This Function is to get the url sitepath with index.php
            *
            * @access public
            * @return string
            */
           public static function sitepath($uri)
           {
                $expression = array_filter(explode('/',($_SERVER['REQUEST_URI'])));
                $find_index = array_search('index.php',$expression);
               $index = (FALSE !== array_search('index.php',$expression)) ? 'index.php/' : '';
                return  self::$url.$index.$uri;
           }


        /**
        * This Function is to encode the url
        *
        * @access public
        * @param  string
        * @return string
        */
        public static function encode($str)
        {
              return urlencode($str);
        }
         /**
        * This Function is to decode the url
        *
        * @access public
        * @param  string
        * @return string
        */
        public static function decode($str)
        {
             return urldecode($str);
        }
}
