<?php  if ( ! defined('CF_SYSTEM')) exit('No External script access allowed');
/**
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
 * @Filename                       :  CF_Config
 * @Description                   : This file is used to load all framework configurations via Registry and store it in order to use it later.
 * @Author                           : Cygnite dev team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    $config = array();
   $config = Config::appsconfig();
    Cygnite::import(CF_SYSTEM.'>cygnite>helpers>CF_Url');
    Config::store_config_items('config_items',$config);
    unset($config);
     //Get the configuration variables
    Url::set_basepath(Config::getconfig('global_config','base_path'));

    class Config
    {
            private static $config = array();

            public static function getconfig($arrkey,$keyval = FALSE)
            {
                    $config = array();

                    $config =  Config::get_config_items('config_items');

                    if(is_null($arrkey))
                           throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);

                     if(FALSE !== array_key_exists($arrkey, $config) && $keyval == FALSE)
                                return $config[$arrkey];

                    if(FALSE !== array_key_exists($arrkey, $config) && $keyval != FALSE)
                          return $config[$arrkey][$keyval];

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

            public static function appsconfig()
            {
                  $config = array();
                 $config['global_config'] = include_once str_replace('/','',APPPATH).DS.'configs'.DS.'config'.EXT;
                 $config['db_config'] = include_once str_replace('/','',APPPATH).DS.'configs'.DS.'database'.EXT;
                 $config['session_config'] = include_once str_replace('/','',APPPATH).DS.'configs'.DS.'session'.EXT;
                 $config['autoload_config'] = include_once str_replace('/','',APPPATH).DS.'configs'.DS.'autoload'.EXT;
                 $config['routing_config'] = include_once str_replace('/','',APPPATH).DS.'routerconfig'.EXT;
                 return $config;
            }
    }