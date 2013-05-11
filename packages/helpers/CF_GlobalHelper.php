<?php
/*
 * ===========================================================================
 *  An open source application development framework for PHP 5.1.6 or newer
 *
 *
 * @access                :  global access
 * @Author              :  Sanjoy Dey
 * @Modified By     :
 * @Warning          :  Any changes in this file can cause abnormal behaviour of the framework
 * @Developed       :  PHP-Ignite Team
 * ===========================================================================
 */

        $CONFIG = CF_AppRegistry::load('Config')->get_config_items('config_items');

        if($CONFIG['ERROR_CONFIG']['log_errors'] == 'on' )
                  CF_AppRegistry::import('base', 'Logger',CF_BASEPATH);// Load the cf Controller Class


    class GlobalHelper
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

        public static function  base_path()
        {
                return CF_AppRegistry::load('Uri')->base_url();
        }

         public static function  site_url_path($url)
        {
                return CF_AppRegistry::load('Uri')->site_url($url);
        }

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
                return CF_ApplicationController::app(CF_ENCRYPT_KEY);
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