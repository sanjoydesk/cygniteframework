<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
    /*
     *===============================================================================================
     *  An open source application development framework for PHP 5.1.6 or newer
     *
     * @Package
     * @Filename                            : CF_Cache.php
     * @Class                               : CF_Cache
     * @Description                         : This library is used to load memory driver libraries based on users need
     * @Author	     	 : Appsntech Dev Team
     * @Copyright	: Copyright (c) 2013 - 2014,
     * @License		: http://www.appsntech.com/license.txt
     * @Link		: http://appsntech.com
     * @Since		: Version 1.0
     * @Filesource
     * @Warning                            : Any changes in this library can cause abnormal behaviour of the framework
     * ===============================================================================================
     */
     class CF_Cache
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
                        try{
                              require_once $path;
                        } catch(Exception $exception) {
                                $exception->getMessage();
                        }
                        if(class_exists($this->driver__class))
                            return new $this->driver__class();
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
                   $this->driver__class = 'CF_'.$this->file_name.'_Driver';
               return (self::$directory != "" && !is_null($this->file_name))
                            ?  str_replace('handler',self::$directory.'\\', dirname(__FILE__)).'cf_'.$this->file_name.'_Driver'.EXT
                           : NULL;
            }
   }
