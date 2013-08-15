<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3  or newer.
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
 * @Filename                       : CF_Cache
 * @Description                   : This factory class is used to load memory driver libraries based on users request
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class Cache
    {
            /* set static variable storage directory  path */
            private static $directory = 'storage';
            /* set variable file name NULL by default */
            private $file_name = NULL;
           /* set variable driver class NULL by default */
            private $driver__class = NULL;

            /*
             *factory pattern to include the driver library and return object of the library
             * @access public
             *@param string $type
             *@return object
             */
            public  function build($type)
            {
                if(is_null($this->file_name))
                        $this->file_name = $type;

                $path = $this->get_path();
                if(is_readable($path)):
                       /* try {
                              require_once $path;
                        } catch(Exception $exception) {
                               echo $exception->getMessage();
                        } */
                        if(class_exists($this->driver_class))
                            return new $this->driver_class();
                        else
                            throw new Exception("Class $cache not found");
                else:
                            throw new Exception("Directory not readable $path");
                endif;

            }
            /*
             * This function is used to get the directory path path on request
             * @access private
             *@return string or boolean
             */
            private function get_path()
            {
                   $this->driver_class = $this->file_name.'_Driver';
               return (self::$directory != "" && !is_null($this->file_name))
                            ?  str_replace('handler',self::$directory.'\\', dirname(__FILE__)).$this->file_name.'_Driver'.EXT
                           : NULL;
            }
   }
