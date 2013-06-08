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

             /*----------------------------------------------------
             * initializeSession
             * ----------------------------------------------------
             */

            if(SECURE_SESSION === TRUE)
                       CF_AppRegistry::load_lib_class('CF_Session');

            //$site_url = CF_AppRegistry::load('Uri')->site_url($base_url);
            $url_string = CF_AppRegistry::load('Uri')->urisegment('1');

             if(empty($url_string))
                     CF_AppRegistry::load('Uri')->redirect($default_controller);

            if(empty($default_controller) || $default_controller == "")
                    throw new ErrorException("Default controller not found ! Please set the default controller in configs/config".EXT);


         /* Check register globals and remove them */
         CF_AppRegistry::load('BaseSecurity')->unset_globals();
         CF_AppRegistry::load('BaseSecurity')->unset_magicquotes();

         /* Rewrite url structure  urlstucture($default_controller);*/
        CF_AppRegistry::load('Uri')->make_request();