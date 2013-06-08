<?php
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


class CF_Profiler
{
        public static $html;
        /**
        * Profiler starting point
        *
        * @access	public
        * @param	string
        */

       public  function start_profiling()
       {
                     if(!defined('MEMORY_START_POINT') && !defined('START_TIME')):
                                 define('MEMORY_START_POINT', $this->memoryspace());
                                 define('START_TIME', microtime(true));
                     endif;
        }

         /**
        * Profiler end point
        *
        * @access	public
        * @param	string
        */

       public function end_profiling()
        {
                    $html .= "<span class='benchmark'>Total elapsed time : ".round(microtime(true) - START_TIME, 3). ' seconds';
                    $html .= " &nbsp; &nbsp; &nbsp;Total memory :".$this->memory_space_usage()."</span>";
                    echo $html;
        }

        private function memoryspace()
        {
            return memory_get_usage();
        }

        /**
        *  Profiler end point
        *
        * @access	public
        * @param	string
        */

       public function memory_space_usage()
       {          //round(memory_get_usage()/1024/1024, 2).'MB';
                    return round((( $this->memoryspace()- MEMORY_START_POINT) / 1024), 2). '  KB<br />';
        }
}