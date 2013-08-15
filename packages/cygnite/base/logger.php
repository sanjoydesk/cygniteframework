<?php
namespace Cygnite\Base;
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
 * @Sub Packages               :  Base
 * @Filename                       :  CF_Logger
 * @Description                   : This class is used to handle error logs of the cygnite framework
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

//AppLogger::write_error_log('Logger Initialized By Sanjay',__FILE__);

class Logger
{
      protected static $log_date_format =  'Y-m-d H:i:s';
      protected static $file_name = NULL;
      protected static $fp = NULL;
      protected static $log_path;
      protected static $log_size;
      protected static $log_ext = ".log";
      protected static $config = array();
      public static $log_errors = '';

      public function __construct()   { }

      private static function get_log_config()
      {
                if(empty(self::$config))
                     self::$config =  Config::getconfig('error_config');

                if(self::$config['log_path'] !="" || self::$config['log_path'] !==NULL)
                        self::$log_path  = APPPATH.self::$config['log_path'].'/';
                else
                        self::$log_path  = APPPATH.'temp/logs/';
               // var_dump(self::$config['ERROR_CONFIG']['log_file_name']);
                self::$file_name  = (self::$config['log_file_name'] !="") ? self::$config['log_file_name']  : 'cf_error_logs';
      }

      public static function read()
      {
           // var_dump( self::$config);
      }

      private static function open($log_file_path)
      {
              self::$fp = fopen($log_file_path, 'a') or exit("Can't open log file ".self::$file_name.self::$log_ext."!");
      }

      public static function write_error_log($log_msg= "",$files_name,$log_level = "log_debug", $log_size = 1)
      {
                 self::get_log_config();

                 $log_file_path = self::$log_path.self::$file_name.'_'.date('Y-m-d').''.self::$log_ext; //exit;
                self::$log_size = $log_size *(1024*1024); // Megs to bytes

                if(self::$config['log_trace_type'] == 2):
                        self::_write($log_msg= "",$files_name,$log_level = "log_debug", $log_size = 1);
                        return TRUE;
                 else:
                       throw new Exception("Log config not set properly in config file. Set log_errors = on and log_trace_type = 2 ");
                endif;

          }

          private function _write($log_msg= "",$files_name,$log_level = "log_debug", $log_size = 1)
          {
               if (!is_resource(self::$fp))
                        self::open($log_file_path);

             /*   if (file_exists($log_file_path)):
                        if (filesize($log_file_path) > self::$log_size):
                                self::$fp = fopen($log_file_path, 'w') or die("can't open file file!");
                                fclose(self::$fp);
                                unlink($log_file_path);
                       endif;
              endif; */

                switch ($log_level):
                            case 'log_debug':
                                        $log_level = "LOG DEBUG :";
                                break;
                           case 'log_info':
                                        $log_level = "LOG INFO : ";
                                break;
                          case 'log_warning':
                                        $log_level = "LOG WARNING : ";
                                break;
                  endswitch;
                 $log_msg = $log_level."  [".date('Y-m-d H:i:s')."] -> [File:  $files_name ] ->  $log_msg".PHP_EOL;// write current time, file name and log msg to the log file

                flock(self::$fp, LOCK_EX);
                fwrite(self::$fp, $log_msg);
                flock(self::$fp, LOCK_UN);
                fclose(self::$fp);

                @chmod($log_file_path,FILE_WRITE_MODE);
                return TRUE;
          }
}