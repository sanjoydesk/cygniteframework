<?php   if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2.5 or newer.
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
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : CF_Profiler
 * @Description                   : This library used to benchmark the code.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */


class Profiler
{
    private static $blocks = array();

    /**
    * Profiler starting point
    *
    * @access	public
    * @param	string
    */
    public static function start($starttoken = 'cygnite_start')
    {
        if(!defined('MEMORY_START_POINT')):
            define('MEMORY_START_POINT', self::memoryspace());
           self::$blocks[$starttoken] = self::get_time();
        endif;
    }

    private static function get_time()
    {
            return microtime(true);
    }

    private static function memory_peak()
    {
        return memory_get_peak_usage(true);
    }

     /**
    * Profiler end point
    *
    * @access	public
    * @param	string
    */

    public static function end($endtoken = 'cygnite_end')
    {
        $html .= "<div id='benchmark'><div class='benchmark'>Total elapsed time : ".round(self::get_time() - self::$blocks[$endtoken], 3). ' s';
        //$html .= self::memory_peak();
        $html .= " &nbsp;&nbsp; &nbsp;Total memory :".self::memory_space_usage()."</div></div>";
        echo $html;
    }
    /**
    * This Function is to get the memory usage by the script
    *
    * @access private
    * @return get memory usage
    */
    private static function memoryspace()
    {
        return memory_get_usage();
    }

    /**
    *  This funtion is to calculate the total memory usage by the running script
    *
    * @access	public
    * @param	string
    */
    public static function memory_space_usage()
    {          //round(memory_get_usage()/1024/1024, 2).'MB';
                 return round((( self::memoryspace()- MEMORY_START_POINT) / 1024), 2). '  KB<br />';
    }
}