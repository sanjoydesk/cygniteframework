<?php
namespace Cygnite\Helpers;

use Cygnite\Cygnite;

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
 * @Filename                       :  GHelper
 * @Description                   :  This helper is used to global functionalities of the framework.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class GHelper
    {
        /*
         * $_POST   = array_map("clearsanity", $_POST);
         * Strip html encoding out of a string, useful to prevent cross site scripting attacks
         * Use this function in view page to display values into web page
         */
        public static function clearsanity($values)
        {
                 (is_array($values)) ? $values = array_map("clearsanity", $values) : $values = htmlentities($values, ENT_QUOTES, 'UTF-8');
             return $values;
        }

        public static function  display_errors($err_type,$err_header,$err_message,$err_file,$line_num = NULL,$debug = FALSE)
        {
                 Cygnite::loader()->errorhandler->handleExceptions($err_type, $err_header,$err_message, $err_file, $line_num,$debug);
        }

         public static function  log_error($messege,$error_code ="",$line_num = "")
        {

        }

         public static function  days_diff($date1)
        {
                  if (!$date1) $date1="0000-00-00 00:00:00";

                  if (preg_match("/(\d+)-(\d+)-(\d+)/",$date1,$f)) {
                    $time_val=mktime(0,0,0,$f[2],$f[3],$f[1]);
                  }
                  $today=mktime(0,0,0,date("m"),date("d"),date("Y"));
                  $s = $today-$time_val;
                  $d = intval($s/86400);
             return $d;
        }

          public static function  get_singleton()
        {
                return \Cygnite\Loader\CF_BaseController::app(CF_ENCRYPT_KEY);
        }

        public static function trace()
        {
                include str_replace('/','',APPPATH).DS.'errors'.DS.'debugtrace'.EXT;
                $output= ob_get_contents();
                ob_get_clean();
                echo $output;
                ob_end_flush();
                ob_get_flush();
        }

}