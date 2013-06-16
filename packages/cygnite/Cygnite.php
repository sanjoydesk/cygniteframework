<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *    http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         : Cygnite Framework BootStrap file
 * @Filename                       : cygnite.php
 * @Description                   : Bootstrap file to auto load core libraries initially.
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                      :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    /*
    * ------------------------------------------------------
    *  Define the Cygnite  Version
    * ------------------------------------------------------
    */
    define('CF_VERSION', ' <span class="version">(alpha 1.0.2)</span>');

    /*----------------------------------------------------
     * Define Framework Extension
     * ----------------------------------------------------
     */
    define('FRAMEWORK_PREFIX','CF_');


    /*----------------------------------------------------
     * Define Database Driver Extension
     * ----------------------------------------------------
     */
    define('DATABASE_PREFIX','DB_');

    /*---------------------------------------------------
     * Define Web root path
     * ---------------------------------------------------
     */
    define('WEB_ROOT',  dirname(__DIR__));

     /* -------------------------------------------------------------------
    *  Define application libraries constant
    * -------------------------------------------------------------------
    */
   define('APPVENDORSDIR',APPPATH.'vendors');

    $seperator = (strstr(strtoupper(substr(PHP_OS, 0, 3)), "WIN")) ?  "\\"  :  "/";
    /*---------------------------------------------------
     * Define Path seperator of Operating System
     * ---------------------------------------------------
     */
    define('OS_PATH_SEPERATOR',$seperator);
  /**
    * -------------------------------------------------------------------------------------------------------
    *  Check minimum version requirement of cygnite and trigger exception is not satisfied
    * -------------------------------------------------------------------------------------------------------
    */
    if(phpversion() < '5.2.5')
           trigger_error('Cygnite supports PHP version  5.2.5 or newer',E_USER_ERROR);


     require_once CF_SYSTEM.DS.'cygnite'.DS.FRAMEWORK_PREFIX.'RobotLoader'.EXT;

     class Cygnite extends CF_RobotLoader
    {
            private static $app = NULL;

            public function __construct()
            {
                  parent::__construct();
            }

            public static function loader()
            {
                     if(is_null(self::$app))
                            self::$app = new self();

                    return self::$app;
            }

            public function request($key, $val = NULL)
           {
                    return parent::request($key, $val);
            }

           public static function  import($path,$dirpath,$filename,$sub_dir = NULL)
                 {
                       if(is_null($path) || $path == "")
                                throw new Exception("Empty path passed on ".__METHOD__);
                      if(is_null($dirpath) || $dirpath == "")
                                throw new Exception("Empty directory name passed on ".__METHOD__);
                     if(is_null($filename) || $filename == "")
                                throw new Exception("Empty file name passed on ".__METHOD__);

                                 switch($path):
                                              case CF_SYSTEM:
                                                                            $prefix = NULL;
                                                                            $path = getcwd().DS.CF_SYSTEM.DS.'cygnite';
                                                                            $prefix = ($dirpath  === 'database') ? DATABASE_PREFIX : FRAMEWORK_PREFIX ;
                                                                            $_directorypath  =    $path.DS.$dirpath.DS.$prefix.$filename.EXT;

                                                                            if(is_readable($_directorypath) && file_exists($_directorypath))
                                                                                      include_once $_directorypath;
                                                                            else
                                                                                     trigger_error ("File Doesn't exist in following path $_directorypath ".__METHOD__,E_USER_WARNING);
                                                          break;
                                              case APPPATH:
                                                                            $path =  getcwd().DS.str_replace('/', '', APPPATH).DS.$dirpath.DS.$filename.EXT;
                                                                           if(is_readable($path))
                                                                                     include_once $path;
                                                                           else
                                                                                    trigger_error ("File Doesn't exist in following path $path ".__METHOD__,E_USER_WARNING);
                                               break;
                                 endswitch;
                     return TRUE;
                 }
                 /*
                  *@warning  You can´t change this!
                  */
                 public static function powered_by()
                 {
                        return 'Cygnite Framework - '.CF_VERSION.' powered by - Sanjoy Dey Productions(http://www.appsntech.com) - Under MIT Licence' ;
                 }

    }
    require_once CF_SYSTEM.DS.'cygnite'.DS.'Strapper'.EXT;