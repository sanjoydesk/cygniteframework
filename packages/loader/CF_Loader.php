<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :   Loader
 * @Filename                       :  CF_Loader
 * @Description                   :  This is the base loader of controller. Controllers extends all base functionality from this BaseController.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

CF_AppRegistry::import('loader', 'AppLoader',CF_SYSTEM);

        class CF_BaseController extends CF_AppLoader
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
                    public function app()
                    {
                        if (self::$instance === NULL)
                             self::$instance = new CF_AppLoader();

                        return self::$instance;
                    }
        }