<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
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
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    class GHelper
    {
        /*
         *
         * $_POST   = array_map("clearsanity", $_POST);
         * Strip html encoding out of a string, useful to prevent cross site scripting attacks
         * Use this function in view page to display values into web page
         */
        public static function clearsanity()
        {
                 (is_array($values)) ? $values = array_map("clearsanity", $values) : $values = htmlentities($values, ENT_QUOTES, 'UTF-8');
             return $values;
        }

      /*   public static function  base_path()
        {
                return CF_AppRegistry::load('Uri')->base_url();
        }

         public static function  site_url_path($url)
        {
                return CF_AppRegistry::load('Uri')->site_url($url);
        } */

         public static function  copyright()
        {
                return CF_AppRegistry::copyright();
        }

        public static function  display_errors($err_type,$err_header,$err_message,$err_file,$line_num = NULL,$debug = FALSE)
        {
                 CF_AppRegistry::load('ErrorHandler')->handle_errors($err_type, $err_header,$err_message, $err_file, $line_num,$debug);
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

          public static function  redirect_to($url)
        {
                 if(is_object(CF_AppRegistry::load('Uri')))
                            CF_AppRegistry::load('Uri')->redirect($url);
        }

          public static function  get_singleton()
        {
                return CF_BaseController::app(CF_ENCRYPT_KEY);
        }

        public function trace()
        {
            /*echo "<title>Unhandle Exception </title>
                <div class='stacktrace' style=''>
                <h2 style='height:30px; border-bottom:1px solid #CCC'> DEBUG MODE: TRACE REQUEST </h2>
                <pre style='word-wrap: break-word;color:#242424;'>";
                 print_r(debug_print_backtrace());
            echo "</pre>
                </div>";*/
            ob_start();
            include str_replace('/','',APPPATH).DS.'errors'.DS.'debugtrace'.EXT;

            $output= ob_get_contents();
            ob_get_clean();

            echo $output;
            ob_end_flush();
            ob_get_flush();
        }

}

     function  show($resultArray = array(),$hasexit ="")
    {
          echo "<pre>";
              print_r($resultArray);
         echo "</pre>";
        if($hasexit === 'exit')
             exit;
    }