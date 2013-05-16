<?php
/*
*===============================================================================================
*  An open source application development framework for PHP 5.2 or newer
*
* @Package                         :
* @Filename                       :
* @Description                   :
* @Autho                            : Appsntech Dev Team
* @Copyright                     : Copyright (c) 2013 - 2014,
* @License                         : http://www.appsntech.com/license.txt
* @Link	                          : http://appsntech.com
* @Since	                          : Version 1.0
* @Filesource
* @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
* ===============================================================================================
* If you don't protect this directory from direct web access, anybody will be able to see your cpmfiguaration and password.
*
* Global Configuration Settings
* config.php
* Set all configuration variables
*
* @access	                   :           public
* @param                     :          $CF_CONFIG array
* @Author                   :           Sanjoy Dey
*/
$CF_CONFIG = array();

$CF_CONFIG = array(
                                  /*
                                  * Set Database Configuration
                                  * To connect Multiple database connection use db2,db3.... etc
                                  * @prototype set database host_name
                                  * @prototype set database username
                                  * @prototype set database password
                                  * @prototype set database name
                                  * @prototype set database prefix
                                  * @prototype set database type
                                  * @prototype set database persistance connection TRUE or FALSE
                                  */
                                    'DB_CONFIG' => array(
                                                            'db' =>array(
                                                                            'host_name' => 'localhost',
                                                                            'username'  => 'root',
                                                                            'password'  => '',
                                                                            'dbname'    => 'cygnite',
                                                                            'dbprefix'  => '',
                                                                            'dbtype'    => 'mysql',
                                                                            'port'        => '',
                                                                            'pconnection' =>FALSE
                                                            )
                                                         /*  ,'db2' => array(
                                                                            'host_name' => 'localhost',
                                                                            'username'  => 'root',
                                                                            'password'  => '',
                                                                            'dbname'    => 'test',
                                                                            'dbprefix'  => '',
                                                                            'dbtype'    => 'mysql',
                                                                            'port'        => '',
                                                                            'pconnection' =>FALSE
                                                            ) */
                                    ),
                                  /*
                                  * Set Global Variables as array
                                  * @prototype set base_path
                                  * @prototype set default_controller
                                  * @prototype set encryption key for encryption library
                                  * @prototype enable profiling TRUE or FALSE
                                  */
                                  'GLOBAL_CONFIG' => array(
                                                                                'base_path'           => 'http://'.$_SERVER['HTTP_HOST'].'/cy/',
                                                                               'default_controller'          => 'welcomeuser',
                                                                                'cf_encryption_key'          => 'cygnite-sha1',
                                                                                'enable_profiling'             => TRUE,
                                                                                'enable_cache'                  => TRUE, //Enable cache bollean TRUE/FALSE
                                                                                'cache_name'                    => 'cf_cache',
                                                                                'cache_extension'             => '.cache',
                                                                                'cache_type'                      => 'filecache',																				 'cache_lifetime'              => '',// 120
                                                                                'cache_directory'              => 'temp/cache',//Default value is none

                                  ),
                                  'ERROR_CONFIG'=> array(
                                                                                    'environment'               =>  'development', //default error config for production is off
                                                                                    'level'                             => 'E_ALL & ~E_DEPRECATED',//E_ALL ^ E_DEPRECATED
                                                                                    'display_errors'            => 'off',
                                                                                    'log_errors'                   => 'on', // Will be available on beta version
                                                                                    //You can set value  1- Display error, 2 - Generate and write into log file
                                                                                    'log_trace_type'         => 2, // Will be available on beta version.
                                                                                    'log_file_name'          => 'application_logs' ,
                                                                                    'log_path'                    => 'temp/logs'

                                  ),
                                   /*
                                  * Set Session Variables as array
                                  * @prototype set
                                  * @prototype set
                                  * @prototype set
                                  * @prototype
                                  */
                                  'SESSION_CONFIG' => array(
                                                                                'cf_session'                             => FALSE , //Set TRUE or FALSE to start session default is FALSE
                                                                                'cf_session_name'                 => 'cf_secure_session',
                                                                                'cf_session_save_path'         => 'default', // Framework default Session path is apps/temp/sessions/
                                                                                'cf_session_cookie_name'   => '',//Need to update code for next version
                                                                                'cf_session_timeout'            => 1440,//Need to update code for next version
                                                                                'cf_session_auto_start'        => '', //Need to update code for next version
                                                                                'cf_session_use_db'              => TRUE, // Need to update code for next version
                                                                                'cf_session_db_name'          => '', // Need to update code for next version
                                                                                'cf_session_table_name'     => 'cf_session', // Need to update code for next version
                                  )
);


/* End of the config.php*/
