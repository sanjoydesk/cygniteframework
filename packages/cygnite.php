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

            /*
            * ------------------------------------------------------
            *  Load the Global Registry Page
            * ------------------------------------------------------
            */
            require dirname(__FILE__).DS.'base'.DS.FRAMEWORK_PREFIX.'AppRegistry'.EXT;
           // is_dir($directoryPath) or mkdir($directoryPath, 0777);

            // Register all framework core directories to include core files
            CF_AppRegistry::register_dir(array(dirname(__FILE__).DS."base",dirname(__FILE__).DS."loader",
                                                                            dirname(__FILE__).DS."library",dirname(__FILE__).DS."helpers"));
           // Auto Load all framework core classes
            CF_AppRegistry::load_lib_class(array('CF_Profiler','CF_ErrorHandler'));
            CF_AppRegistry::import('helpers', 'Config',CF_SYSTEM);

            $CF_CONFIG = Config::get_config_items('config_items');


           /* Set Environment for Application
            *
            * Example :
            *  define('DEVELOPMENT_ENVIRONMENT', 'development');
            * define('DEVELOPMENT_ENVIRONMENT', 'production');
            */
            define('APP_ENVIRONMENT', $CF_CONFIG['ERROR_CONFIG']['environment']);
            CF_AppRegistry::load('ErrorHandler')->set_environment($CF_CONFIG['ERROR_CONFIG']);



            if($CF_CONFIG['GLOBAL_CONFIG']['enable_profiling']==TRUE)
                     CF_AppRegistry::load('Profiler')->start_profiling();

            CF_AppRegistry::load_lib_class(array('CF_Uri','CF_BaseSecurity','CF_AppAutoLoader'));

            // get instance of the core files
            CF_AppRegistry::import('loader', 'Loader',CF_SYSTEM);// Load the cf Controller
            CF_AppRegistry::import('helpers', 'GHelper',CF_SYSTEM);
            CF_AppRegistry::import('loader', 'AppLibraryRegistry',CF_SYSTEM);

            $AUTOLOAD = CF_AppRegistry::load('AppAutoLoader')->get_autoload_items('autoload_items');

            CF_AppLibraryRegistry::initialize($AUTOLOAD['autoload']);
            //   show($CF_CONFIG);

            //Get the configuration variables
            $default_controller = $CF_CONFIG['GLOBAL_CONFIG']["default_controller"];
            $base_url = $CF_CONFIG['GLOBAL_CONFIG']["base_path"];
            $secret_key = $CF_CONFIG['GLOBAL_CONFIG']['cf_encryption_key'];

            define('SECURE_SESSION', $CF_CONFIG['SESSION_CONFIG']['cf_session']);

            CF_AppRegistry::load('Uri')->set_base_url($base_url);

            /*
            * ------------------------------------------------------
            *  Define the Cygnite Encryption Key ID
            * ------------------------------------------------------
            */
            if(!empty($secret_key) && in_array('encrypt',$AUTOLOAD['autoload']['helpers']) || $securesession === TRUE)
                      define('CF_ENCRYPT_KEY',$secret_key);
            require dirname(__FILE__).DS.'boot'.EXT;