<?php
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