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

/*
*---------------------------------------------------------------------------------
* AUTO-LOADER
* --------------------------------------------------------------------------------
* This file is used to specify which files should be loaded by default.
*
*
* Created Date   : 05-07-2012
* Modified Date  : 06-07-2012
*/

$CF_CONFIG['autoload'] = array(
                                                        'helpers' => array(''), /* Autoload Helper Files */
                                                        'libraries' => array('authx'), /* Autoload Library Files*/
                                                        'plugins' => array(), /* Autoload Library Files*/
                                                        'model'    => array()   /* Autoload Model Files*/
);