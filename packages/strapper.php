<?php if ( ! defined('CF_BASEPATH')) exit('Direct script access not allowed');

        /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.1.6 or newer
         *
         * @Package                         :
         * @Filename                       : PhpIngite.php
         * @Description                   : This file is used to auto load all base libraries
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         *
         */



            /*
            * ------------------------------------------------------
            *  Define the Phcfgnite Version
            * ------------------------------------------------------
            */
            define('CF_VERSION', '1.0');

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
            *  Load the global Page
            * ------------------------------------------------------
            */
            require CF_BASEPATH.DS.'base'.DS.FRAMEWORK_PREFIX.'AppRegistry'.EXT;

            $common_directory = CF_BASEPATH .OS_PATH_SEPERATOR."base";
            $loader_directory =    CF_BASEPATH.OS_PATH_SEPERATOR."loader";
            $library_directory =   CF_BASEPATH .OS_PATH_SEPERATOR."library";
            $helpers_directory = CF_BASEPATH .OS_PATH_SEPERATOR."helpers";
           // is_dir($directoryPath) or mkdir($directoryPath, 0777);

            // Register all framework core directories to include core files
            CF_AppRegistry::register_dir(array($common_directory,$loader_directory,$library_directory,$helpers_directory));
           // Auto Load all framework core classes
            CF_AppRegistry::load_lib_class(array('CF_Config','CF_Profiler','CF_ErrorHandler'));

             CF_AppRegistry::load('Config')->init_config();

            $CF_CONFIG = CF_AppRegistry::load('Config')->get_config_items('config_items');


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

            CF_AppRegistry::load_lib_class(array('CF_Uri','CF_Security','CF_AppAutoLoader'));


            // get instance of the core files
            CF_AppRegistry::import('loader', 'Loader',CF_BASEPATH);// Load the cf Controller
            CF_AppRegistry::import('helpers', 'GlobalHelper',CF_BASEPATH);
           // CF_AppRegistry::import('loader', 'AppLibraryRegistry',CF_BASEPATH);

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
            *  Define the Phcfgnite Encryption Key ID
            * ------------------------------------------------------
            */
            if(!empty($secret_key) && in_array('encrypt',$AUTOLOAD['autoload']['helpers']) || $securesession === TRUE)
                      define('CF_ENCRYPT_KEY',$secret_key);
            require dirname(__FILE__) .OS_PATH_SEPERATOR.'boot'.EXT;