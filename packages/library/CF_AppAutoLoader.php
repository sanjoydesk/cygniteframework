<?php  if ( ! defined('CF_BASEPATH')) exit('Direct script access not allowed');

/*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
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