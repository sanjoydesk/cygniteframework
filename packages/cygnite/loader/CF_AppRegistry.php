<?php
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
 * @SubPackages               :   Loader
 * @Filename                       :  CF_AppRegistry
 * @Description                   :  This applibraryregistry loader is used to to load all library,helpers,plugins file and models
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class CF_AppRegistry implements CF_IRegistry
{
        private static $_register_dir = array();
        private static $_library = NULL;
        private static $_helpers = NULL;
        private static $_plugins = NULL;
        private static $_models = NULL;

        public static function initialize($dirRegistry = array())
        {
             self::$_register_dir = $dirRegistry;
             $auto_load_apps_dirs = array();
            $plugins_dir = str_replace('/', '',APPPATH).DS.'vendors'.DS."plugins";
            $helpers_dir = str_replace('/', '',APPPATH).DS.'vendors'.DS."helpers";

             // Register all framework core directories to include core files
            if(!empty(self::$_register_dir['helpers'])):
                    Cygnite::import(str_replace('/','',APPPATH).'>vendors>helpers>'.self::$_register_dir['helpers']);
            endif;

            if(!empty(self::$_register_dir['plugins'])):
                     Cygnite::import(str_replace('/','',APPPATH).'>vendors>plugins>'.self::$_register_dir['plugins']);
            endif;

            if(!empty(self::$_register_dir['model'])): // Auto Load all framework core classes
                  Cygnite::import(str_replace('/','',APPPATH).'>models>'.self::$_register_dir['model']);
            endif;
        }

        public function apps_load($key)
        {
                if($key != NULL || $key != "")
                        return Cygnite::loader()->request($key);
                else
                        throw new ErrorException ("Invalid argument passed on ".__FUNCTION__);
        }
}