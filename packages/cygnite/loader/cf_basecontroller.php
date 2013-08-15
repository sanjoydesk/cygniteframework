<?php
namespace Cygnite\Loader;

use  Cygnite\Sparker\CFView as CF_View;
use Cygnite\Loader\Apploader as CF_Apploader;

if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
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

class CF_BaseController extends CF_View
{

    public static $instance;


    /**
    * Constructor function
    * Creating Object for load class
    *
    * @access	public
    * @return	load class object
    *
    */
    public function __construct(){  }

    //prevent clone.
    public function __clone(){}



    /**
    * Returns singleton instance of the class
    * @return object
    */
    public function app()
    {
      if (is_null(self::$instance))
           self::$instance = new CF_Apploader();

      return self::$instance;
    }

    public static function getins()
    {

    }
 }