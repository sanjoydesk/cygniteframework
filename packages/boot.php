<?php
        /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.1.6 or newer
         *
         * @Package                         :
         * @Filename                       : boot.php
         * @Description                   : This file is used to auto load all base libraries
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
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
         CF_AppRegistry::load('Security')->unset_globals();
         CF_AppRegistry::load('Security')->unset_magicquotes();

         /* Rewrite url structure  urlstucture($default_controller);*/
        CF_AppRegistry::load('Uri')->make_request();