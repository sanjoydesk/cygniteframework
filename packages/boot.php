<?php

/*
 * Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *    http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         : Cygnite Framework BootStrap file
 * @Filename                       : boot.php
 * @Description                   : Boot file to make request to controllers.
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                      :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    /*----------------------------------------------------------------
    * Autoload Session library based on user configurations
    * ----------------------------------------------------------------
    */
    if(SECURE_SESSION === TRUE)
           CF_AppRegistry::load_lib_class('CF_Session');
   /*------------------------------------------------------------------------------------
    * Throw Exception is default controller has not been set in configuration
    * ------------------------------------------------------------------------------------
    */
    if(empty($gconfig["default_controller"]) || $gconfig["default_controller"] == "")
            throw new ErrorException("Default controller not found ! Please set the default controller in configs/config".EXT);

    /*-----------------------------------------------------------------------------------------------
     * Check register globals and remove them. Secure application by build in libraries
     * -----------------------------------------------------------------------------------------------
     */
    CF_AppRegistry::load('BaseSecurity')->unset_globals();
    CF_AppRegistry::load('BaseSecurity')->unset_magicquotes();

    /*-------------------------------------------------------
     * Booting completed. Lets handle user request!!
     * Lets Go !!
     * -------------------------------------------------------
     */
    CF_AppRegistry::load('RequestHandler')->handle();