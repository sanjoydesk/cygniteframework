<?php  if ( ! defined('CF_SYSTEM')) exit('No direct script access allowed');
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
 * @Filename                       :  CF_Config
 * @Description                   : This file is used to load all framework configurations via Registry and store it in order to use it later.
 * @Author                           : Cygnite dev team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    CF_AppRegistry::import('configs', 'config',APPPATH);
    Config::store_config_items('config_items',CF_AppRegistry::load('config_items'));

    class Config
    {
            private static $config = array();

            public static function getconfig($arrkey,$keyval = FALSE)
            {
                    $config =  Config::get_config_items('config_items');

                    if(is_null($arrkey))
                           throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
                     $arrk = strtoupper($arrkey);

                     if(FALSE !== array_key_exists($arrk, $config) && $keyval == FALSE)
                                return $config[$arrk];

                    if(FALSE !== array_key_exists(strtoupper($arrkey), $config) && $keyval != FALSE)
                          return $config[$arrk][$keyval];
            }

            public static function store_config_items($name, $values = array())
            {
                  self::$config[$name]  = $values;
            }

            public static function get_config_items($key)
            {
                if(is_null($key))
                           throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
                  return self::$config[strtolower($key)];
            }
    }