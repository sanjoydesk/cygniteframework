<?php //namespace Cygnite;
if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3 or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so that I can send you a copy immediately.
 *
 * @Package                         : Cygnite Framework BootStrap file
 * @Filename                       : cygnite.php
 * @Description                   : Bootstrap file to auto load core libraries initially.
 * @Author                         : Sanjoy Dey
 * @Copyright                   :  Copyright (c) 2013 - 2014,
 * @Link	                 :  http://www.cygniteframework.com
 * @Since	                 :  Version 1.0
 * @Filesource
 * @Warning                    :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    /*
    * ------------------------------------------------------
    *  Define the Cygnite  Version
    * ------------------------------------------------------
    */
    defined('CF_VERSION') OR define('CF_VERSION', ' <span class="version">(alpha 1.0.2)</span>');

    /*----------------------------------------------------
     * Define Framework Extension
     * ----------------------------------------------------
     */
   defined('FRAMEWORK_PREFIX') OR define('FRAMEWORK_PREFIX','CF_');

     /* -------------------------------------------------------------------
    *  Define application libraries constant
    * -------------------------------------------------------------------
    */
   define('APPVENDORSDIR',APPPATH.'vendors');

    $seperator = (strstr(strtoupper(substr(PHP_OS, 0, 3)), "WIN")) ?  "\\"  :  "/";

     if (!phpversion() <  '5.3')
    {
            @set_magic_quotes_runtime(0); // Kill magic quotes
    }

  /**
    * -------------------------------------------------------------------------------------------------------
    *  Check minimum version requirement of cygnite and trigger exception is not satisfied
    * -------------------------------------------------------------------------------------------------------
    */
    if(phpversion() < '5.2.5')
           trigger_error('Cygnite supports PHP version 5.2.8 or newer',E_USER_ERROR);

     require CF_SYSTEM.DS.'cygnite'.DS.'CFRobotLoader'.EXT;

     class Cygnite extends CFRobotLoader
    {
            private static $app = NULL;
          /**
            * ------------------------------------------------------------------------------------------
            * Cygnite Constructor
            * -----------------------------------------------------------------------------------------
            *Call parent construct
            */
            public function __construct()
            {
                  parent::__construct();
            }
           /**
            * ------------------------------------------------------------------------------------------
            * Return Singleton object of Cygnite
            * -----------------------------------------------------------------------------------------
            * The loader method is used to return singleton object of Cygnite
            *@return object
            */
            public static function loader()
            {
                     if(is_null(self::$app))
                            self::$app = new self();

                    return self::$app;
            }
           /**
            * ------------------------------------------------------------------------------------------
            * Request libraries and classs
            * -----------------------------------------------------------------------------------------
            * This method is used to return library object
            *@return object
            */
            public function request($key, $val = NULL)
           {
                    return parent::request($key, $val);
            }
            /**
            * ------------------------------------------------------------------------------------------
            * Import application files
            * -----------------------------------------------------------------------------------------
            * This method is used to import application and system helpers and plugins.
            *@return bool
            */
           public static function  import($path)
          {
                         if(is_null($path) || $path == "")
                                throw new Exception("Empty path passed on ".__METHOD__);
                              $dirpath = getcwd().DS.str_replace('>',DS,$path).EXT;

                        if(is_readable($dirpath) && file_exists($dirpath)):
                                   return include_once $dirpath;
                        else:
                                 echo  '<pre>';print_r(debug_print_backtrace()); echo'</pre>';
                                trigger_error ("Requested file doesn't exist in following path $dirpath ".__METHOD__,E_USER_WARNING);
                        endif;
         }
        /**
        * ------------------------------------------------------------------------------------------
        * Load model
        * -----------------------------------------------------------------------------------------
        * This method is used to load model dynamically and return model object
         * from Cygnite Robot
        *@return object
        */
         public function model($key, $val = NULL)
        {
            return parent::models($key,$val);
        }


         /*
          *@warning  You can´t change this!
          */
         public static function powered_by()
         {
                return 'Cygnite Framework - '.CF_VERSION.' Powered by - Sanjoy Productions (<a href="http://www.cygniteframework.com">http://www.cygniteframework.com</a>)' ;
         }
    }
    require_once CF_SYSTEM.DS.'cygnite'.DS.'Strapper'.EXT;