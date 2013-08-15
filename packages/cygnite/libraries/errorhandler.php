<?php
namespace Cygnite\Libraries;

use Cygnite\Helpers\Config;


if ( ! defined('CF_SYSTEM')) exit('No External script access allowed');
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
 * @Sub Packages               :  Library
 * @Filename                       : CF_ErrorHandler
 * @Description                   : This library used to handle all errors or exceptions of Cygnite Framework.
 * @Author                           :  Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
       $logconfig = Config::getconfig('error_config','log_errors');
              if($logconfig == 'on' )
                  \Cygnite\Cygnite::import('packages.cygnite.base.logger');// Load the Logger Library


    class Errorhandler
    {

           private $debug = FALSE;
           private $ob_level;
            /*
             *  Constructor function             *
             */
           public  function __construct()
           {
                    $this->ob_level = ob_get_level();
                   set_error_handler(array($this, 'handleExceptions'));//Use our custom handler
            }

            public   function handleExceptions($err_type, $err_header = "Error Encountered",$err_message, $err_file = NULL, $line = NULL,$debug = NULL)
           {
                    $this->debug = $debug;
                    switch($err_type):
                                case E_USER_ERROR :
                                            switch ($this->debug):
                                                        case FALSE:
                                                                     $this->_error($err_header,$line,$err_message,$err_file,$this->debug,'');
                                                                  //  exit;
                                                        case TRUE:
                                                                    $this->_error($err_header,$line,$err_message,$err_file,$this->debug,'fatal');
                                            endswitch;
                                    break;
                                case E_USER_WARNING :
                                                    $this->_error($err_header,$line,$err_message,$err_file,'','warning');
                                    break;
                                case E_USER_NOTICE :
                                                $this->_error($err_header,$line,$err_message,$err_file,'','notice');
                                    break;
                    endswitch;

            }

            private function _error($title,$line,$err_message,$err_file,$debug = "",$type = NULL)
            {
                        ob_start();
                        $arr =  array(
                                      'title' => $title,
                                      'line' =>$line,
                                      'message'=>$err_message,
                                      'type' =>$type,
                                      'debug'=>$debug,
                                      'file' =>$err_file
                        );
                        @extract($arr);
                        include APPPATH.DS.'errors'.DS.'error'.EXT;

                        $output= ob_get_contents();
                        ob_get_clean();
                        echo $output;
                        ob_end_flush();
                        ob_get_flush();
                        ($debug === TRUE || $debug === 1) ? '' : $debug;
                        ($type === 'fatal') ?  exit : '';
            }

            public function  set_environment($err_config = array())
            {
                    /** Check if environment is development and display errors **/
                    switch(APP_ENVIRONMENT):
                                case 'development':
                                            error_reporting(-1);
                                        /*    error_reporting($err_config['level']);
                                          ini_set('display_errors',$err_config['display_errors']);
                                          ini_set('log_errors', $err_config['log_errors']);
                                         ini_set('error_log', ROOTDIR.DS.str_replace("/", "", APPPATH).DS.'tmp'.DS.'logs'.DS.'error.log'); */
                                break;
                                case 'production':
                                            error_reporting($err_config['level']);
                                            ini_set('display_errors',$err_config['display_errors']);
                                            ini_set('log_errors', $err_config['log_errors']);
                                            ini_set('error_log', ROOTDIR.DS.APPPATH.DS.'tmp'.DS.'logs'.DS.'error.log');
                                break;
                    endswitch;

            }

           public function trigger_my_error($message, $level)
            {
                    //Get the caller of the calling function and details about it
                    $callee = next(debug_backtrace());
                    //Trigger appropriate error
                    trigger_error($message.' in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['line'].'</strong>', $level);
            }



            //-------------------------------
            //Demo usage: abc(18); //Error: abc() expects parameter 1 to be a string in [FILE].php on line line number
            //-------------------------------
            function abc($str)
            {
                    if(!is_string($str))
                              trigger_my_error('abc() expects parameter 1 to be a string', E_USER_ERROR);
            }

            function __destruct() {  }
    }