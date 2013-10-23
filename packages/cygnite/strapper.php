<?php
namespace Cygnite;

use Cygnite\Helpers\Url;
use Cygnite\Helpers\Config;
use Cygnite\Helpers\Profiler;

if (defined('CF_SYSTEM') === false) {
    exit('External script access not allowed');
}

/**
* Cygnite Framework
*
* An open source application development framework for PHP 5.3x or newer
*
* License
*
* This source file is subject to the MIT license that is bundled
* with this package in the file LICENSE.txt.
* http://www.cygniteframework.com/license.txt
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to sanjoy@hotmail.com so I can send you a copy immediately.
*
* @Package                 :  Cygnite Framework BootStrap file
* @Filename                :  strapper.php
* @Description             :  Bootstrap file to auto load core libraries initially.
* @Author                  :  Sanjoy Dey
* @Copyright               :  Copyright (c) 2013 - 2014,
* @Link	                   :  http://www.cygniteframework.com
* @Since	               :  Version 1.0
* @FilesSource
* @Warning                 :  Any changes in this library can cause
*                             abnormal behaviour of the framework
*
*
*/

/**
 *--------------------------------------------------
 * Initialize all core helpers and start booting
 *
 * Turn on benchmarking application is profiling is on
 * in configuration
 *--------------------------------------------------
 */

if (Config::getConfig('global_config', 'enable_profiling') == true) {
      Profiler::start('cygnite_start');
}

/**
* Set Environment for Application
* Example:
* <code>
* define('DEVELOPMENT_ENVIRONMENT', 'development');
* define('DEVELOPMENT_ENVIRONMENT', 'production');
* </code>
*/
define('MODE', Config::getConfig('global_config', 'environment'));

/** ----------------------------------------------------------------------
 *  Set Cygnite user defined encryption key and start booting
 * ----------------------------------------------------------------------
 */
if (!is_null(Config::getConfig('global_config', 'cf_encryption_key')) ||
    in_array('encrypt', Config::getConfig('autoload_config', 'helpers')) == true ) {
    define('CF_ENCRYPT_KEY', Config::getConfig('global_config', 'cf_encryption_key'));
}

/**----------------------------------------------------------------
 * Get Session config and set it here
 * ----------------------------------------------------------------
 */
define('SECURE_SESSION', Config::getConfig('session_config', 'cf_session'));

/**----------------------------------------------------------------
 * Auto load Session library based on user configurations
 * ----------------------------------------------------------------
 */
if (SECURE_SESSION === true) {
    Cygnite::loader()->session;
}


/**------------------------------------------------------------------
 * Throw Exception is default controller
 * has not been set in configuration
 * ------------------------------------------------------------------
 */
if (is_null(Config::getConfig('global_config', "default_controller"))) {
    trigger_error(
        "Default controller not found ! Please set the default
                    controller in configs/application".EXT
    );
}

/**-------------------------------------------------------------------
 * Check register globals and remove them.
 * Secure application by build in libraries
 * -------------------------------------------------------------------
 */
//Cygnite::loader()->security->sanitize($_POST);

$filename = preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);

if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

if (MODE == 'development') {
    $events->trigger("exception");
    Cygnite::loader()->exceptions->setEnvironment(Config::getConfig('error_config'));
} else {
    ini_set('display_error', 0);
    error_reporting(0);
}

Cygnite::import('apps.routes');

 // Before Router Middleware
$router->before(
    'GET',
    '/.*',
    function () {
        //show(headers_list());
        if (!headers_sent()) {
            header('X-Powered-By: Cygnite Router');
        }
    }
);

function onExceptions()
{
    $whoops = new \Whoops\Run();

    //Configure the PrettyPageHandler:
    $errorPage = new \Whoops\Handler\PrettyPageHandler();

    $errorPage->setPageTitle("Unhandled Exception!"); // Set the page's title
    $errorPage->setEditor("sublime");//Set the editor used for the "Open" link

    $errorPage->addDataTable(
        "Cygnite Framework  ",
        array(
            "version" => Cygnite::version()
        )
    );

    $whoops->pushHandler($errorPage);
    $whoops->register();

}
/**-------------------------------------------------------
 * Booting completed. Lets handle user request!!
 * Lets Go !!
 * -------------------------------------------------------
 */
 Cygnite::loader()->request('dispatcher', $router);

 Profiler::end('cygnite_start');
