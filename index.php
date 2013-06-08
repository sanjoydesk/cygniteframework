<?PHP
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
 * @Package                         : Cygnite Framework
 * @Filename                       : index.php
 * @Description                   : This index file is entry point of the framework to define framework base paths.
 * @Author                        : Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                      :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */


  /*---------------------------------------------------------------
    * Define Directory Separator
    *---------------------------------------------------------------
    */
    define('DS',DIRECTORY_SEPARATOR);

   /*---------------------------------------------------------------
     * Define URI Separator
     *---------------------------------------------------------------
     */
    define('URIS', '/');

     /* -------------------------------------------------------------------
    *  Define PHP file extension
    * -------------------------------------------------------------------
    */
    define('EXT', '.php');

  /* -------------------------------------------------------------------
    *  Now that we know the path, set the main path constants.
   *   Path to the packages folder
    * -------------------------------------------------------------------
    */
    define('CF_SYSTEM', 'packages');

  /* -------------------------------------------------------------------
    *  Define application folder name
    * -------------------------------------------------------------------
    */
   // str_replace('\\',"/",getcwd());
    define('APPPATH', 'apps/');
    //echo APPPATH;
    /* -------------------------------------------------------------------
    *  Name of the Root Directory
    * -------------------------------------------------------------------
    */
    define('ROOT_DIR', str_replace("/", "", rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/')));

    define('ROOT', basename(__DIR__));


  /* -------------------------------------------------------------------
    *  Defined application FPATH
    * -------------------------------------------------------------------
    */
    define('FPATH', $_SERVER['DOCUMENT_ROOT'].ROOT_DIR.'/');

  /* -------------------------------------------------------------------
    *  Lets start to include default core files
    * -------------------------------------------------------------------
    */

    require_once CF_SYSTEM.DS.'cygnite'.EXT;