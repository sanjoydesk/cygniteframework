<?php
namespace Cygnite;

if (!defined('CF_SYSTEM')) {
    exit('External script access not allowed');
}

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3 or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so that I can send you a copy immediately.
 *
 * @Package             :  Cygnite Framework BootStrap file
 * @Filename            :  cygnite.php
 * @Description         :  Bootstrap file to auto load core libraries initially.
 * @Author              :  Sanjoy Dey
 * @Copyright           :  Copyright (c) 2013 - 2014,
 * @Link	            :  http://www.cygniteframework.com
 * @Since	            :  Version 1.0
 * @File Source
 * @Warning             :  Any changes in this library can cause
 *                         abnormal behaviour of the framework
 *
 *
 */


class Cygnite extends RobotLoader
{

    private static $instance;

    /**
     * ---------------------------------------------------
     * Cygnite Constructor
     * ---------------------------------------------------
     * Call parent init method
     */

    public function __construct()
    {
        parent::init();
    }

    /**
     * ----------------------------------------------------
     * Return Singleton object of Cygnite
     * ----------------------------------------------------
     *
     * The loader method is used to return singleton object
     * of Cygnite
     *
     * @return object
     */
    public static function loader()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;

    }

    public static function version()
    {
        return CF_VERSION;
    }

    /**
     * @warning You can't change this!
     * @return string
     */
    public static function poweredBy()
    {
        return 'Cygnite Framework - '.CF_VERSION.' Powered by -
            Sanjoy Productions (<a href="http://www.cygniteframework.com">
            http://www.cygniteframework.com
            </a>)';

    }


}
 Cygnite::loader();
 require_once CF_SYSTEM.DS.'cygnite'.DS.'strapper'.EXT;