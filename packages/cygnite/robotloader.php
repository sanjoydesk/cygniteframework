<?php
namespace Cygnite;

if (!defined('CF_SYSTEM')) {
    exit('External script access not allowed');
}
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 *@package                         :  Packages
 *@subpackages                :  Base
 *@filename                        :  CF_RobotLoader
 *@description                    : This is registry auto loader for CF
 *@author                           : Sanjoy Dey
 *@copyright                     :  Copyright (c) 2013 - 2014,
 *@link	                   :  http://www.cygniteframework.com
 *@since	                  :  Version 1.0
 *@filesource
 *@warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class RobotLoader
{
    /**
     * Cygnite Framework Core Classes Prefix
     */

     private static $coreprefix = 'CF_';

     private static $instance = array();

    /**
     * ----------------------------------------------------------
     * Register all core classes to Cygnite Robot Engine
     * ----------------------------------------------------------
     * Never Manually change any core classes in this section
     * If you would like to add new class into our Robot Engine
     * then you can just register it on autoload Libraries which will
     * be added dynamically to Engine else send me patches will
     * update core class.
     *
     */

     public static $_classNames = array(
        'Dispatcher' =>  '\\Cygnite\\Base\\Dispatcher',
        'Router' =>  '\\Cygnite\\Base\\Router',
        'IRouter' =>  '\\Cygnite\\Base\\IRouter',
        'Events' =>  '\\Cygnite\\Base\\Events',
        'Encrypt'  =>  '\\Cygnite\\Libraries\\Encrypt',
        'Exceptions'  =>  '\\Cygnite\\Exceptions',
        'Security'  =>  '\\Cygnite\\Security',
        'Upload'  =>  '\\Cygnite\\Libraries\\Upload',
        'Validator' => '\\Cygnite\\Libraries\\Validator',
        'Forms'  =>  '\\Cygnite\\Libraries\\Forms',
        'Mailer'  =>  '\\Cygnite\\Libraries\\Mailer',
        'Inflectors'  =>  '\\Cygnite\\Inflectors',
        'Email'  =>  '\\Cygnite\\Libraries\\Phpmailer\\Email',
        'Zip'  =>  '\\Cygnite\\Libraries\\Zip',
        'Session'  =>  '\\Cygnite\\Libraries\\Session',
        'StorageInterface'  =>  '\\Cygnite\\Libraries\\Cache\\StorageInterface',
        'Apc'  =>  '\\Cygnite\\Libraries\\Cache\\Storage\\Apc',
        'CfMemcache'  =>  '\\Cygnite\\Libraries\\Cache\\Storage\\CfMemcache',
        'Pdf'  =>  '\\Cygnite\\Libraries\\Pdf',
        'Parser'  =>  '\\Cygnite\\Libraries\\Parser',
        'BaseController'  =>  '\\Cygnite\\BaseController',
        'CFView'  =>  '\\Cygnite\\CFView',
        'Singleton' => '\\Cygnite\\Singleton',
        'Cookie' => '\\Cygnite\\Cookie',
        'CookiesInterface' => '\\Cygnite\\CookiesInterface',
        'Configurations' => '\\Cygnite\\Database\\Configurations',
        'DatabaseExceptions'  =>  '\\Cygnite\\Database\\Exceptions\\DatabaseExceptions',
        'Schema'  =>  '\\Cygnite\\Database\\Schema',
        'ActiveRecords'  =>  '\\Cygnite\\Database\\ActiveRecords',
        'Connections'  =>  '\\Cygnite\\Database\\Connections',
        'Table'  =>  '\\Cygnite\\Database\\Table',
        'Datasource'  =>  '\\Cygnite\\Database\\Datasource',
        'Transactions'  =>  '\\Cygnite\\Database\\Transactions',
        'Joins'  =>  '\\Cygnite\\Database\\Joins',
        'GHelper'=>'\\Cygnite\\Helpers\\GHelper',
        'Config'=>'\\Cygnite\\Helpers\\Config',
        'Url'=>'\\Cygnite\\Helpers\\Url',
        'Profiler'=>'\\Cygnite\\Helpers\\Profiler',
        'Assets'=>'\\Cygnite\\Helpers\\Assets',
     );

    public $loadedClass = array();

    private function __construct()
    {

    }

    protected function init()
    {
        set_include_path(get_include_path().DS.APPPATH);
        set_include_path(get_include_path().DS.CF_SYSTEM);
        spl_autoload_unregister(array($this, 'autoLoad'));
        spl_autoload_extensions(".php");
        spl_autoload_register(array($this, 'autoLoad'));
    }

    private static function changeCase($string, $isLower = false)
    {
        return ($isLower === false)
            ? strtolower($string)
            : ucfirst($string);
    }

    /**
     *----------------------------------------------------------
     * Auto load all classes
     *----------------------------------------------------------
     * All classes will get auto loaded here.
     *
     * @param $className
     * @throws \Exception
     * @internal param string $className
     * @return boolean
     */
    private function autoLoad($className)
    {

        $path  = $rootDir ='';
        $_classNames =array();

        $_classNames = array_flip(self::$_classNames);

        //var_dump($_classNames);

        if (!array_key_exists(
            $_classNames['\\'.self::changeCase($className, true)],
            self::$_classNames
        )) {
            throw new \Exception("Error occurred while loading class $className");
        }

        try {
            if (array_key_exists(
                $_classNames['\\'.self::changeCase($className, true)],
                self::$_classNames
            )
            ) {

                if (preg_match(
                    "/Apps/i",
                    self::$_classNames[self::changeCase($_classNames['\\'.$className], true)]
                )
                ) {
                    $rootDir = $ds=  '';
                } else {
                    $rootDir = CF_SYSTEM.DS;
                    $ds = DS;
                }

                $path = ltrim(
                    self::changeCase(
                        str_replace(
                            array('\\','>','.'
                            ),
                            DS,
                            self::$_classNames[self::changeCase($_classNames['\\'.$className], true)]
                        )
                    ).EXT,
                    '/'
                );

                $includePath = "";
                $includePath = getcwd().$ds.$rootDir.$path;

                if (is_readable($includePath)) {
                    return include_once str_replace(array('//', '\\\\'), array('/', '\\'), $includePath);
                } else {
                    throw new \Exception("Requested class $className not found!!");
                }

            } //else {
        } catch (Exception $ex) {
            throw new \Exception("Error occurred while loading class $className");
        }
    }

    public function __get($key)
    {
        $class = $libpath = "";

        if (!array_key_exists(ucfirst($key), self::$_classNames)) {
            throw new \Exception("Requested $key Library not exists !!");
        }

        $class = self::$_classNames[ucfirst($key)];

        if (preg_match("/Apps/i", $class)) {
             $rootDir = $ds=  '';
        } else {
             $rootDir = CF_SYSTEM;
             $ds = DS;
        }

        $libpath = getcwd().$ds.$rootDir.strtolower(
            str_replace(
                array('\\', '.', '>', ''
                ),
                DS,
                $class
            )
        ).EXT;

        if (is_readable($libpath) && class_exists($class)) {

            if (!isset(self::$instance[$class])) {
                self::$instance[$class] = new $class;
            }

            return self::$instance[$class];
            //You cannot pass parameters to constructor of the class
        } else {
            throw new \Exception("Requested class not readable or class not exists on $libpath");
        }
    }

    /*
    * Call magic method to register classes and models dynamically into cygnite engine
    * @param $name method name
    * @args $args array passed into the method
    */
    public function __call($name, $args)
    {
        if ($name == 'registerClasses' || $name == 'registerModels') {
            call_user_func_array(array($this, 'setClasses'), $args);
        } else {
            throw new \Exception("Invalid method $name");
        }
    }

    /**
    * ----------------------------------------------------------
    * Set classes dynamically
    * ----------------------------------------------------------
    * Set classes dynamically to Robot engine
    *
    *@param $args unknown
    *@return void
    *
    */
    public function setClasses($args)
    {
        switch ($args) {
            case is_array($args):
                foreach ($args as $key => $value) {
                    self::$_classNames[self::changeCase($key, true)] = $value;
                }
                break;
            default:
                self::$_classNames[self::changeCase(@$args[0], true)] = @$args[1];
                break;
        }
    }


    /**
    * --------------------------------------------------------------------
    * Import application files
    * --------------------------------------------------------------------
    * This method is used to import application
    * and system helpers and plugins.
    *
    * @param $path
    * @throws \Exception
    * @return bool
    */
    public static function import($path)
    {
        if (is_null($path) || $path == "") {
            throw new \InvalidArgumentException("Empty path passed on ".__METHOD__);
        }

        $dirPath = getcwd().DS.str_replace(array('>', '.'), DS, $path).EXT;

        if (is_readable($dirPath) && file_exists($dirPath)) {
            return include_once $dirPath;
        } else {
            throw new \Exception("Requested file doesn't exist in following path $dirPath ".__METHOD__);
        }
    }

    /**
    * ----------------------------------------------------------
    * Request a object of the class
    * ----------------------------------------------------------
    * This method is used to request classes from
    * Cygnite Engine and  return library object
    *
    * @param $key string
    * @param $val NULL
    * @throws \Exception
    * @return object
    */
    public function request($key, $val = null)
    {
        $class = $libPath = "";

        if (!array_key_exists(ucfirst($key), self::$_classNames)) {
            throw new \Exception("Requested $class Library not exists !!");
        }

        $class = self::$_classNames[ucfirst($key)];
        $libPath = getcwd().DS.CF_SYSTEM.strtolower(
            str_replace(
                array(
                     '\\',
                     '.',
                     '>'
                ),
                DS,
                $class
            )
        ).EXT;

        if (is_readable($libPath) && class_exists($class)) {
            if (!isset(self::$instance[$class])) {
                self::$instance[$class] = new $class($val);
            }

            return self::$instance[$class];
            // You cannot pass parameters to constructor of the class
        } else {
            throw new \Exception("Requested class not available on $libPath");
        }
    }


    /**
    * -----------------------------------------------------------------
    * Get all loaded classes
    * -----------------------------------------------------------------
    * This method is used to return all registered class names
    * from cygnite robot
    *@return array
    */
    public function registeredClasses()
    {
        return self::$_classNames;
    }

    /**
     * -------------------------------------------------------------------
     * Load models and return model object
     * -------------------------------------------------------------------
     * This method is used to load all models dynamically
     * and return model object
     *
     * @param $key string
     * @param $val string
     * @throws \Exception
     * @return object
     */
    public function model($key, $val = null)
    {
        $class = $libPath = "";
        $class = ucfirst(trim($key));

        if (!array_key_exists($class, self::$_classNames)) {
            throw new \Exception("Requested $class Library not exists !!");
        }

        $libPath = strtolower(APPPATH).DS.'models'.DS;

        if (is_readable($libPath) && class_exists($class)) {
            return new $class();
        } else {
            throw new \Exception("Path not readable $libPath");
        }

    }
}