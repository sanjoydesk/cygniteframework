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
 * @Sub Packages               :  Base
 * @Filename                       :  CF_BaseSecurity
 * @Description                   : This class is used to provide base security to the framework
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

 class CF_BaseSecurity
{


      function __construct() { }

      public function _xss_clean() {

      }

      public function check_sanity() {

          $this->_chk_email_sanity($email_input);
      }

      private function _chk_email_sanity($email_input = "") {
                $sanitized_email = filter_var($email_input, FILTER_SANITIZE_EMAIL);
                if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                             echo "This sanitized email address is considered valid";
                } else {
                            echo "This sanitized email address is considered invalid.\n";
                }
      }

      public function unset_globals()
      {
                if (ini_get('register_globals')):
                        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
                        foreach ($array as $value):
                            foreach ($GLOBALS[$value] as $key => $var):
                                if ($var === $GLOBALS[$key])
                                    unset($GLOBALS[$key]);
                                endforeach;
                        endforeach;
               endif;
      }

      public  function unset_magicquotes()
      {
                if ( get_magic_quotes_gpc() ):
                        $_GET    = $this->sanityclean($_GET );
                        $_POST   = $this->sanityclean($_POST);
                        $_COOKIE = $this->sanityclean($_COOKIE);
                endif;
     }

    /** Check for Magic Quotes and remove them **/
    function sanityclean($value)
     {
                $value = is_array($value) ? array_map('sanityclean', $value) : stripslashes($value);
                return $value;
    }

    /**
    * Strips unwanted whitespace from output
    *
    * @param string $string String to sanitize
    * @return string whitespace sanitized string
    */
    private function remove_whitespace($string)
    {
            $trimmed_value = preg_replace('/[\n\r\t]+/', '', $string);
            return preg_replace('/\s{2,}/u', ' ', $trimmed_value);
    }

    public function strip($string)
    {
                $string = $this->remove_whitespace($string);
                $string = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $string);
                $string = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $string);
                $string = preg_replace('/<img[^>]*>/i', '', $string);

                return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/is', '', $string);
    }

 }