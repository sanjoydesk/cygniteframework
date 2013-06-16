<?php if ( ! defined('CF_SYSTEM')) exit('No direct script access allowed');
/**
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
 * @Package                          :  Apps
 * @Sub Packages                 :
 * @Filename                        :  router_config
 * @Description                    : This file is used to set all routing configurations
 * @Author                           : Cygnite dev team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                   :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *@todo                           :  Multiple routiing configurations have to implemented and have to simplify
 *                                           core code for routing feature, have to add more filter validation.
 *
 */

/*
*---------------------------------------------------------------------------------
* ROUTER CONFIG
* --------------------------------------------------------------------------------
* This file is used to enable routing
*
*
* Created Date   : 05-07-2012
* Modified Date  : 06-07-2012
*/
abstract class Route
{
    public static $path,$routeto;
    public static $routing = array();

    public static function set_route()
    {
       return self::$routing = array(
                              'is_router_enabled' => TRUE,
                              'url'=>'category/list',
                              'routeto' => 'welcomeuser@testing'
                              );
    }

    public static function get_route()
    {
        if(!is_null(self::set_route()))
            return self::set_route();

        return FALSE;
    }

}