<?php
/*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */

class CF_Profiler
{

        /**
        * Profiler starting point
        *
        * @access	public
        * @param	string
        */

       public  function start_profiling()
       {
                     if(!defined('MEMORY_START_POINT') && !defined('START_TIME')):
                                 define('MEMORY_START_POINT', memory_get_usage());
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
                    echo "<div style='margin:40px;float:right;'>Total elapsed time : ".round(microtime(true) - START_TIME, 3). ' seconds';
                    echo " &nbsp; &nbsp; &nbsp;Total memory :".$this->memory_space_usage()."</div>";
        }

        /**
        *  Profiler end point
        *
        * @access	public
        * @param	string
        */

       public function memory_space_usage()
       {          //round(memory_get_usage()/1024/1024, 2).'MB';
                    return round(((memory_get_usage() - MEMORY_START_POINT) / 1024), 2). '  KB<br />';
        } 
}