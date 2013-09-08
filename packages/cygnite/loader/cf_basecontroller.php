<?php
namespace Cygnite\Loader;

use  Cygnite\Sparker\CFView;
use Cygnite\Loader\Apploader;

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
 * @Package                         :  Packages
 * @SubPackages               :   Loader
 * @Filename                       :  CF_Loader
 * @Description                   :  This is the base loader of controller. Controllers extends all base functionality from this BaseController.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class CF_BaseController extends CFView
{

    public static $instance;
    /**
     * Constructor function
     * Creating Object for load class
     *
     * @access    public
     * @return \Cygnite\Loader\CF_BaseController class object
     */
    public function __construct()
    {
        parent::__construct();
    }

    //prevent clone.
    public function __clone(){}

    /**
     * Magic Method for handling errors.
     *
     */
    public function __call($method, $parameters)
    { 
        throw new \Exception("Method [$method] is not defined on the Base Controller.");
    }

    public static function getins()
    {

    }
 }
