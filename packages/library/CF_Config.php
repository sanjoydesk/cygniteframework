<?php  if ( ! defined('CF_BASEPATH')) exit('No direct script access allowed');

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

    class CF_CONFIG
    {
            var $value = array();
            /*
             *  Constructor function
             * @param string - encryption key
             *
             */
            function __construct(){ }

           public function init_config()
            {
                   CF_AppRegistry::import('configs', 'config',APPPATH);
                   $this->store_config_items('config_items',CF_AppRegistry::load('config_items'));

            }

            public function get_config_items($key)
            {
                 $key =  strtolower($key);
                  return $this->value[$key];
            }

            private  function store_config_items($name, $values = array())
            {
                  $this->value[$name]  = $values;
            }

            function __destruct()
            {
                unset($this);
            }
    }