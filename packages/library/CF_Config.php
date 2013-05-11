<?php  if ( ! defined('CF_BASEPATH')) exit('No direct script access allowed');


/*===============================================================================
*  An open source application development framework for PHP 5.1.6 or newer
*
*  This file is to encrypt string
* @access public
* @Author          :         Sanjoy Dey
* @Modified By :
* @Warning      :         Any changes in this file can cause abnormal behaviour of the framework
* @Developed   :         PHP-ignite Team
* @Framework Version 1.0
* ================================================================================
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