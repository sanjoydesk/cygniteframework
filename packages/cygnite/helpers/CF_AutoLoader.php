<?php  if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
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
 * @Filename                       :  CF_AutoLoader
 * @Description                   : This file is used to auto load all libraries,helpers,plugins, models via Registry and store into registry to use it.
 * @Author                           : Cygnite dev team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    AutoLoader::set_autoload_items('autoload_items',Config::getconfig('autoload_config'));
    $autoloaded = AutoLoader::get_autoload_items('autoload_items');
    var_dump($autoloaded);
    CF_AppRegistry::initialize($autoloaded['autoload']);

    class CF_AutoLoader
    {
            public static $value = array();

            public static function get_autoload_items($key)
            {
                 $key =  strtolower($key);
                  return self::$value[$key];
            }

            public static function set_autoload_items($name, $values = array())
            {
                self::$value[$name]  = $values;
            }
    }