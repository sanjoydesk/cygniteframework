<?php
/*
*=========================================================================
*  An open source application development framework for PHP 5.1.6 or newer
*
* cf_Loader.php
* cf_Controller class
* Inheriting load class and its properties
*
* @access	                   public
* @param                             $load, $db variable
* @Author                 :         Sanjoy Dey
* @Modified By        :
* @Warning             :         Any changes in this file can cause abnormal behaviour of the framework
* @Developed By    :         PHP-ignite Team
*=========================================================================
*/

CF_AppRegistry::import('loader', 'AppLoader',CF_BASEPATH);

        class CF_ApplicationController extends CF_AppLoader
        {

                  public static $instance = NULL;

                  /**
                    * Constructor function
                    * Creating Object for load class
                    *
                    * @access	public
                    * @return	load class object
                    *
                    */
                   public function __construct(){  }

                   //prevent clone.
                   public function __clone(){}

                    /**
                    * Returns singleton instance of the class
                    * @return object
                    */
                    public static function app()
                    {
                        if (self::$instance === NULL)
                             self::$instance = new CF_AppLoader();

                        return self::$instance;
                    }
                      function __destruct()
                     {
                           //  unset($this);
                             //unset(self::$instance);
                     }
        }