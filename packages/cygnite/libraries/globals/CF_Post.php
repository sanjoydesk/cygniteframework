<?php
/*
 *  Cygnite Framework
 *  An open source application development framework for PHP 5.2x or newer
 *
 *  Post class that inherits Global base class and implements SecureData interface
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
 * @Sub Packages               :  Library
 * @Filename                       :  CF_POST
 * @Description                   : This class is used to handle session mechanisam of the cygnite framework
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class CF_Post extends Globals implements SecureData
{
            public $_var = "_POST";

            public function is_posted($input)
            {
                    return  filter_has_var(INPUT_POST, $input) ? TRUE : FALSE;
            }


            /**
             * Sets new value for given POST variable.
             * @param string $variable Post variable name
             * @param mixed $value     New value to be set.
             */
            public static function setpost( $variable, $value="")
            {
                $_POST[$variable] = $value;
                if(is_array($variable)):
                       foreach($variable as $key=>$val):
                                $_POST[$key] = $this->clean_variables($val);
                       endforeach;
                endif;
            }

             public function getpost($key = NULL, $default = NULL)
            {
                if (NULL === $key)
                      return $this->clean_variables($_POST);
                else
                    return (isset($_POST[$key])) ? $this->clean_variables($_POST[$key]) : $default;
            }

}