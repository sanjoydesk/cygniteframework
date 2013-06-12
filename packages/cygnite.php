<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');

/*
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

    include_once 'helpers'.DS.FRAMEWORK_PREFIX.'GHelper'.EXT;
    include_once 'helpers'.DS.FRAMEWORK_PREFIX.'Config'.EXT;
    include_once 'helpers'.DS.FRAMEWORK_PREFIX.'Profiler'.EXT;
   /**
    * --------------------------------------------------------------------------------
    *  Turn on benchmarking application is profiling is on in configuration
    * --------------------------------------------------------------------------------
    */
    $gconfig = Config::getconfig('global_config');
      if($gconfig['enable_profiling']==TRUE)
             Profiler::start();
  /**
    * ------------------------------------------------------
    *  Load the Global Registry Page
    * ------------------------------------------------------
    */
    require dirname(__FILE__).DS.'base'.DS.FRAMEWORK_PREFIX.'AppRegistry'.EXT;
   // is_dir($directoryPath) or mkdir($directoryPath, 0777);

    // Register all framework core directories to include core files
    CF_AppRegistry::register_dir(array(dirname(__FILE__).DS."base",dirname(__FILE__).DS."loader",
                                                                    dirname(__FILE__).DS."library",dirname(__FILE__).DS."helpers"));

    CF_AppRegistry::load_lib_class('CF_ErrorHandler');
    /* Set Environment for Application
    * Example :
    *  define('DEVELOPMENT_ENVIRONMENT', 'development');
    * define('DEVELOPMENT_ENVIRONMENT', 'production');
    */
    define('APP_ENVIRONMENT', Config::getconfig('error_config','environment'));
    CF_AppRegistry::load('ErrorHandler')->set_environment(Config::getconfig('error_config'));

   /*----------------------------------------------------------------
    * Auto load core libraries classes of Cygnite Framework
    * ----------------------------------------------------------------
    */
    CF_AppRegistry::load_lib_class(array('CF_RequestHandler','CF_BaseSecurity','CF_AppAutoLoader'));

   /*----------------------------------------------------------------
    * Import initial core classes of Cygnite Framework
    * ----------------------------------------------------------------
    */
    CF_AppRegistry::import('loader', 'Loader',CF_SYSTEM);// Load the Base Controller
    CF_AppRegistry::import('loader', 'AppLibraryRegistry',CF_SYSTEM);
    CF_AppRegistry::import('helpers', 'AutoLoader',CF_SYSTEM);
   /*----------------------------------------------------------------
    * Get Session config and set it here
    * ----------------------------------------------------------------
    */
    define('SECURE_SESSION', Config::getconfig('session_config','cf_session'));

   /* ----------------------------------------------------------------------
    *  Set Cygnite user defined encryption key and start booting
    * ----------------------------------------------------------------------
    */
    if(!is_null(Config::getconfig('global_config','cf_encryption_key')) && in_array('encrypt',$AUTOLOAD['autoload']['helpers']) || SECURE_SESSION === TRUE)
              define('CF_ENCRYPT_KEY',Config::getconfig('global_config','cf_encryption_key'));
    require dirname(__FILE__).DS.'boot'.EXT;