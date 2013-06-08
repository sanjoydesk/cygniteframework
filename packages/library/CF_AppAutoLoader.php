<?php  if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
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
 * @Sub Packages               :  Library
 * @Filename                       :  CF_AppAutoLoader
 * @Description                   : This file is used to auto load all libraries,helpers,plugins, models via Registry and store into registry to use it.
 * @Author                           : Cygnite dev team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class CF_AppAutoLoader
    {
            var $value = array();
            /*
             *  Constructor function
             * @param string - encryption key
             *
             */
            function __construct(){
                    CF_AppRegistry::import('configs', 'autoload',APPPATH);
                   $this->store_autoload_items('autoload_items',CF_AppRegistry::load('autoload_items'));
           }

            public function get_autoload_items($key)
            {
                 $key =  strtolower($key);
                  return $this->value[$key];
            }

            private  function store_autoload_items($name, $values = array())
            {   //var_dump($values);
                  $this->value[$name]  = $values;
            }

            function __destruct() {
                unset($this->value);
            }
    }