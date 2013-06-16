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

    Cygnite::import(CF_SYSTEM,'helpers', 'GHelper');
    Cygnite::import(CF_SYSTEM,'helpers', 'Config');
    Cygnite::import(CF_SYSTEM,'helpers', 'Profiler');

   /**
    * --------------------------------------------------------------------------------
    *  Turn on benchmarking application is profiling is on in configuration
    * --------------------------------------------------------------------------------
    */
      if(Config::getconfig('global_config','enable_profiling')==TRUE)
             Profiler::start();


      /* Set Environment for Application
    * Example :
    *  define('DEVELOPMENT_ENVIRONMENT', 'development');
    * define('DEVELOPMENT_ENVIRONMENT', 'production');
    */
    define('APP_ENVIRONMENT', Config::getconfig('error_config','environment'));
    Cygnite::loader()->request('ErrorHandler')->set_environment(Config::getconfig('error_config'));

  /*----------------------------------------------------------------
    * Import initial core classes of Cygnite Framework
    * ----------------------------------------------------------------
    */
    Cygnite::import(CF_SYSTEM,'loader', 'BaseController');// Load the Base Controller
    Cygnite::import(CF_SYSTEM,'helpers', 'AutoLoader');

   /* ----------------------------------------------------------------------
    *  Set Cygnite user defined encryption key and start booting
    * ----------------------------------------------------------------------
    */
    if(!is_null(Config::getconfig('global_config','cf_encryption_key')) || in_array('encrypt', Config::getconfig('autoload_config','helpers')))
              define('CF_ENCRYPT_KEY',Config::getconfig('global_config','cf_encryption_key'));

  /*----------------------------------------------------------------
    * Get Session config and set it here
    * ----------------------------------------------------------------
    */
    define('SECURE_SESSION', Config::getconfig('session_config','cf_session'));


    /*----------------------------------------------------------------
    * Autoload Session library based on user configurations
    * ----------------------------------------------------------------
    */
    if(SECURE_SESSION === TRUE)
           Cygnite::loader()->request('Session');
   /*------------------------------------------------------------------------------------
    * Throw Exception is default controller has not been set in configuration
    * ------------------------------------------------------------------------------------
    */
    if(is_null(Config::getconfig('global_config',"default_controller")) || Config::getconfig('global_config',"default_controller") == "")
            throw new ErrorException("Default controller not found ! Please set the default controller in configs/config".EXT);

    /*-----------------------------------------------------------------------------------------------
     * Check register globals and remove them. Secure application by build in libraries
     * -----------------------------------------------------------------------------------------------
     */
      Cygnite::loader()->request('Security');
     //Cygnite::loader()->request('BaseSecurity')->unset_globals();
    //Cygnite::loader()->request('BaseSecurity')->unset_magicquotes();

    /*-------------------------------------------------------
     * Booting completed. Lets handle user request!!
     * Lets Go !!
     * -------------------------------------------------------
     */
    Cygnite::loader()->request('RequestHandler')->handle();