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
 * @Sub Packages               :  Base
 * @Filename                       :  CF_Uri
 * @Description                   : This is the base entry point for user request and dispatch to requested controllers.
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

//namespace common\cf_Uri;
 //AppLogger::write_error_log(' URI Initialized',__FILE__);
    CF_AppRegistry::import('base', 'Dispatcher',CF_SYSTEM);
    CF_AppRegistry::import('base', 'RouteMapper',CF_SYSTEM);

    class CF_Uri
    {
            private $index_page = "index.php";
            var $values =  NULL;
            private  $router_enabled = FALSE;

            private function _is_enabled()
            {
                try{
                       include_once APPPATH.'routerconfig'.EXT;
                       if(class_exists('Route'))
                          $route = Route::get_route();

                       }  catch (Exception $excep) {
                            $excep->getMessage();
                       }
                     if(TRUE=== $route['is_router_enabled']):
                            $this->router_enabled = TRUE;
                            return $route;
                    endif;

                    return FALSE;
            }

            //=====================================================//
            /*
            * Url Structure Function is to make mvc structure url
            *
            * @access	public
            *
            */
         public function make_request($profiler = NULL)
           {
                       $expression  = $querystring = array();
                       $querystring = explode('?',$_SERVER['REQUEST_URI'],2);
                       $calee = debug_backtrace();
                       if(!empty($querystring[1]))
                                  GHelper::display_errors(E_USER_WARNING, 'Bad Url Format ', 'You are not allowed to pass query string in url.', __FILE__,$callee[0]['line'] ,TRUE);

                      $expression = array_filter(explode('/',($_SERVER['REQUEST_URI'])));

                      if($routeconfig = $this->_is_enabled()):
                           $router = $routeconfig;
                            if($routeconfig['url'] =="")
                                  throw new Exception ("Invalid url parameter on Route::set_route()");
                            if($routeconfig['routeto'] =="")
                                  throw new Exception ("Invalid routeto parameter on Route::set_route()");
                       endif;

                      $segment = explode('/', $router['url']);

                    $find_index = array_search($this->index_page,$expression);

                //    echo $find_index;exit;
                          if($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] == $segment[0] && $expression[4] == $segment[1])):
                                //echo "here I am with routing and index page and match all uri <br>";
                                 RouteMapper::route($router['url'],$router['routeto']);
                          elseif($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] != $segment[0] && $expression[4] != $segment[1])):
                                 //echo "here I am with routing and index page but not match uri<br>";
                                Dispatcher::response_user_request($expression, $find_index);
                         elseif($find_index == '' && ($expression[2] == $segment[0] && $expression[3] == $segment[1]) ):
                                //echo "here m nw elseif";
                                RouteMapper::route($router['url'], $router['routeto']);
                        elseif($find_index == '' && TRUE == $this->_is_enabled() && ($expression[2] != $segment[0] && $expression[3] != $segment[1])):
                            // echo "here I am with routing and without index page <br>";
                             $find_indexcount = array_search(ROOT,$expression);
                            Dispatcher::response_user_request($expression, $find_indexcount);
                            //   RouteMapper::route($segment[0], $segment[1]);
                        elseif($find_index == '' && ($expression[2] == $segment[0] && $expression[3] != $segment[1]) ):
                                 Dispatcher::response_user_request($expression, $find_index);
                       else: //echo "jhhjhj";
                           RouteMapper::route($router['url'],$router['routeto']);
                         //  Dispatcher::response_user_request($expression, $find_index);
                        endif;


           }


                //prevent clone.
                public function __clone(){}



                public function set_base_url($url)
                {
                          $this->values  = $url;
                }


               public function base_url()
               {
                     return $this->values;
               }

               public function site_url($uri)
               {

                    return $this->values.$this->index_page.URIS.$uri;
               }

             public function redirect($uri = '', $type = 'location', $http_response_code = 302)
            {
               if ( ! preg_match('#^https?://#i', $uri)):
                      $uri = $this->site_url($uri);
                endif;

                        switch($type):
                            case 'refresh' :
                                                  header("Refresh:0;url=".$uri);
                                     break;
                            default:
                                                 header("Location: ".$uri, TRUE, $http_response_code);
                                    break;
                      endswitch;
                    exit;
            }

             public static function redirect_to( $url )
            {
                    if( !headers_sent() ):
                        header('Location: '. $url);
                    else:
                        echo '<script type="text/javascript">';
                        echo 'location.href = "'. $url .'";';
                        echo '</script>';
                    endif;
            }

              public  function urisegment($uri="")
             {       //echo $uri;
                      return Dispatcher::get_url_segment($uri);
             }
                /*
                 *  This method is used to destroy the global variables of the class
                 * @param variable
                 * @param variable
                 *
                 */
                function __destruct()
                {
                       unset($this->index_page);
                }
    }
