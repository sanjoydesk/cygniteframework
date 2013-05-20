<?PHP
       /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.1.6 or newer
         *
         * @Package                         :
         * @Filename                       : index.php
         * @Description                   :
         * @Author                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */

             // Define Directory Separator
            define('DS',DIRECTORY_SEPARATOR);

             // Define URI Separator
            define('URIS', '/');

            /*
             *---------------------------------------------------------------
             * Core FOLDER NAME
             *---------------------------------------------------------------
             */
            $core_path = 'packages';

            /*
             *---------------------------------------------------------------
             * APPLICATION FOLDER NAME
             *---------------------------------------------------------------
             */
            $application_folder = 'apps';

            /*
             * -------------------------------------------------------------------
             *  Now that we know the path, set the main path constants
             * -------------------------------------------------------------------
             */


            // The PHP file extension
            define('EXT', '.php');
            // Path to the system folder
            define('CF_BASEPATH', str_replace("\\", "/", $core_path));

            //Name of the Root Directory
            define('ROOT_DIR', str_replace("/", "", rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/')));

            define('APPPATH', $application_folder.'/');

            define('FPATH', $_SERVER['DOCUMENT_ROOT'].ROOT_DIR.'/');

            require_once CF_BASEPATH.'/strapper'.EXT;