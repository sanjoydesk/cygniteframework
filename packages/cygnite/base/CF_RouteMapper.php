<?php
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
 * @Package                         :  Packages
 * @Sub Packages               :  Base
 * @Filename                       :  CF_RouteMapper
 * @Description                   : This file is used to map all routing of the cygnite framework
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */


//RouteMapper::get('welcomeuser/(:all?)', 'category@view');
//RouteMapper::route('category/any','industry@index');
//RouteMapper::route('category/any','welcome@testing');

    class RouteMapper
    {
            private static $index_page = "index.php";
            //public static $is_router_enabled = FALSE;
           // public static $url = NULL;

            public static function route($url,$routeto)
            {
                 $segment = explode('/', $url);

                 if(in_array('any', $segment) == TRUE && self::_uri_exists($segment[0], $_SERVER['REQUEST_URI']) == TRUE):
                   //    echo "controll exists with any keyword ";
                                $call_route = array();
                                $call_route = explode('@', $routeto);
                                $exp = explode('/',($_SERVER['REQUEST_URI']));
                                 $index_count = array_search(self::$index_page,$exp);
                                  $index = ($index_count) ? self::$index_page : '';
                                 $route_url =    $_SERVER['SCRIPT_NAME'].'/'.$call_route[0].'/'.$call_route[1];
                                // var_dump($exp);
                                $expression = explode('/', $route_url);
                                $find_index = array_search(self::$index_page,$expression);
                                self::catch_request($expression,$find_index);
                 else:
                        if(self::_uri_exists($segment, $_SERVER['REQUEST_URI'])):
                               $call_route = array();
                               $call_route = explode('@', $routeto);
                               $exp = array_filter(explode('/',($_SERVER['REQUEST_URI'])));

                               $index_count = array_search(self::$index_page,$exp);
                               $index = ($index_count) ? '/'.self::$index_page : '/'.self::$index_page;
                               $route_url = str_replace('/'.self::$index_page, '', $_SERVER['SCRIPT_NAME']).$index.'/'.$call_route[0].'/'.$call_route[1];

                               $expression = explode('/', $route_url);
                               $find_index = array_search(self::$index_page,$expression);
                               self::catch_request($expression,$find_index);
                        endif;
                 endif;
            }

            private static function catch_request($expression,$find_index)
            {
                        Cygnite::import(CF_SYSTEM,'base', 'Dispatcher');
                        if(class_exists('Dispatcher')): //var_dump(array_filter($expression));
                                    Dispatcher::response_user_request(array_filter($expression),$find_index,TRUE);
                        endif;
            }

            public static function _uri_exists($var, $content, $type='any')
            {
                switch ($var):
                    case is_array($var):
                                    if(self::_uri_exists($var[0], $_SERVER['REQUEST_URI']) && self::_uri_exists($var[1], $_SERVER['REQUEST_URI']) ):
                                                return TRUE;
                                    endif;
                        break;
                    case is_string($var):
                                    return strpos(strtolower($content),
                                                            strtolower($var)) ?
                                                            TRUE : FALSE;
                        break;

                endswitch;
            }
    }