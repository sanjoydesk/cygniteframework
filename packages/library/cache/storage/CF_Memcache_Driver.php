<?php

    /*
     *===============================================================================================
     *  An open source application development framework for PHP 5.1.6 or newer
     *
     * @Package
     * @Filename                             : CF_Memcache_Driver.php
     * @Class                                    : CF_Memcache_Driver
     * @Description                         : This library is used to store , retrive and destroy data from memcache memory.
     *                                                     Use of this library to boost up application performance.
     * @Author	     	 : Appsntech Dev Team
     * @Copyright	: Copyright (c) 2013 - 2014,
     * @License		: http://www.appsntech.com/license.html
     * @Link		: http://appsntech.com
     * @Since		: Version 1.0
     * @Filesource
     * @Warning                            : Any changes in this library can cause abnormal behaviour of the framework
     * ===============================================================================================
     */

    /**
    * @require Abstract storage class to implement APC Cache
    */
    require_once '../IMemoryStorage'.EXT;
    
    class CF_Memcache_Driver extends IMemoryStorage
    {
            /* Public variable $is_enabled boolean FALSE by default. isenable set as True if Memcache extension available. */
            public $is_enabled = FALSE;
            /* Private variable $memobj default NULL. Store memcache object */
            private $memobj = NULL;
            /* Private variable $host NULL. set hostname based on user inpu */
            private $host = NULL;
            /* Private variable $port NULL. set port to connect with memcache based on user input. */
            private $port = NULL;


            /*
             * Constructor function to check availability of memcache ext class throw error on unavailability
             *
             */
            public function __construct()
            {
                    if (!class_exists('Memcache'))
                        throw new Exception("Memcache extension not available !");
            }
             /**
            * Connect memcache based on its host and port. Connect with default port if hostname and port number not passed
            *
            * @param string $host
            * @param mix $port
            * @return void
            */
            public function add_server($host = "",$port="")
            {
                    $args = func_get_args();
                        if(empty($args)):
                            $this->host = 'localhost'; $this->port = 11211;
                        else:
                            $this->host = $host; $this->port = $port;  endif;
                            if (class_exists('Memcache')):
                                    if($this->memobj == NULL):
                                         $this->memobj = new Memcache();

                                      $this->is_enabled = TRUE;
                                              if (! $this->memobj->connect($this->host,$this->port)): // Instead 'localhost' here can be IP
                                                  $this->memobj = NULL;
                                                  $this->is_enabled = TRUE;
                                              endif;
                                    endif;
                             endif;
            }
            /*
             * Prevent cloning
             */
            final private function __clone() {}
            /*
             * Private store function
             */
            private function store($key,$value) {}
            /*
             * Call the function to save data into memcache
             * @param name key
             * @param args value to be stored
             */
            public function __call($name, $args)
            {
                   return call_user_func_array(array($this,'set_data'), $args);
            }

            /**
            * Store the value in the memcache memory (overwrite if key exists)
            *
            * @param string $key
            * @param mix $value
            * @param bool $compress
            * @param int $excfre (seconds before item excfres)
            * @return bool
            */
            protected function set_data($key, $value, $compress=0, $excfre=600)
            {
                     if(is_null($key) || $key == "")
                         throw new Exception ("Empty key passed ".__FUNCTION__);
                     if(is_null($value) || $value == "")
                         throw new Exception ("Empty key passed ".__FUNCTION__);

                    //Used MEMCACHE_COMPRESSED to store the item compressed (uses zlib).  $this->life_time
                    return $this->memobj->set($key, $value, $compress ? MEMCACHE_COMPRESSED:NULL,$excfre_time);
            }
             /**
            * Get data from memory based on its key
            *
            * @param string $key
            * @return bool
            */
            public function get_data($key)
            {
                    $data = array();
                    $data = $this->memobj->get($key);
                    return (FALSE === $data) ? NULL : $data;
            }
             /**
            * Delete values from memory based on its key
            *
            * @param string $key
            * @return bool
            */
            public function destroy($key)
            {
                     if(is_null($key) || $key == "")
                        throw new Exception ("Empty key passed ".__FUNCTION__);

                     return $this->memobj->delete($key);
            }
            /*
             * Destructor function to unset variables from the memory to boost up performance
             */
            public function __destruct()
            {
                    unset($this->memobj);unset($this->host); unset($this->port);
            }
    }