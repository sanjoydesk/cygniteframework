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
 * @Filename                       :  CF_Dispatcher
 * @Description                   : This class is used to handle user request
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class Dispatcher
    {

             private static $default_method = "index";
             private static $controller = NULL;
             private static $method = NULL;
             private static $args = array();
             private static $instance = NULL;

            public static function response_user_request($expression,$find_index,$router = FALSE)
            {
                    $config =  Config::getconfig('global_config');
                    $default_controller_name = $config['default_controller'];

                    if($find_index && (array_key_exists($find_index+1,$expression)) ===TRUE && $router == FALSE):
                               self::$controller = ucfirst($expression[$find_index+1]).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                               self::$method = strtolower($expression[$find_index+2]);
                               self::$args = array_slice($expression,$find_index+3);
                               self::import_controller($expression[$find_index+1]);
                               self::call_requested_controller();
                     elseif($find_index && (array_key_exists($find_index+1,$expression)) === FALSE):
                              self::$controller = ucfirst($default_controller_name).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                              self::$method = strtolower(self::$default_method);
                              self::import_controller($default_controller_name);
                              self::call_requested_controller();
                     elseif($find_index && (array_key_exists($find_index+1,$expression)) ===TRUE && $router == TRUE):
                              self::$controller = ucfirst($expression[$find_index+1]).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                              self::$method = strtolower($expression[$find_index+2]);
                              $exp = array_filter(explode('/',$_SERVER['REQUEST_URI']));

                              if($exp[$find_index] == 'index.php'):
                                           self::$args = array_slice($exp,$find_index+2);
                              else:
                                          $find_indexcount = array_search(ROOT_DIR,$expression);
                                           self::$args = array_slice($exp,$find_indexcount+2);
                              endif;
                               self::import_controller($expression[$find_index+1]);
                               self::call_requested_controller();
                     elseif($find_index ==FALSE && (array_key_exists($find_index+2,$expression)) != FALSE): // echo $expression[$find_index+2];
                              self::$controller = ucfirst($expression[$find_index+2]).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                               self::$method = strtolower($expression[$find_index+3]);
                              self::$args = array_slice($expression,$find_index+3);

                              self::import_controller($expression[$find_index+2]);
                              self::call_requested_controller();
                     elseif($find_index ="" && is_null(self::get_self_instance())):
                             // echo "If index file not available";
                             $find_index = array_search(CSDIR,$expression);
                             self::$controller = ucfirst($default_controller_name).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                             self::$args = array_slice($expression,$find_index+2);
                             self::import_controller($default_controller_name);
                             self::call_requested_controller();
                     endif;
                     unset($config);
            }

            private static function import_controller()
           {
                     $args = func_get_args();
                     if(is_readable(str_replace("/",DS,APPPATH)."controllers".DS.strtolower($args[0]).EXT)):
                          include_once str_replace('/', '',APPPATH).OS_PATH_SEPERATOR."controllers".OS_PATH_SEPERATOR.strtolower($args[0]).EXT; //FPATH.'/'.
                     else:
                            GHelper::trace();
                            $callee = debug_backtrace();
                            GHelper::display_errors(E_USER_ERROR,'Server Error: 404 (Not Found)',
                                                                 "Couldn't find the requested page $args[0]. Please check the url.",$callee[1]['file'],$callee[1]['line'], TRUE);
                     endif;
           }

           private static function call_requested_controller()
           {
                    //var_dump(preg_replace('/[^a-zA-Z0-9]/', '', self::$controller));
                    self::setselfObject(preg_replace('/[^a-zA-Z0-9]/', '', self::$controller));

                    $viewdir = strtolower(str_replace('AppsController','',self::$controller));
                    $path= str_replace('/','',APPPATH).DS.'views'.DS.$viewdir.DS;

                    if(empty(self::$method)|| self::$method == ""):
                        self::set_view_dir($path);
                        call_user_func_array(array(self::get_self_instance(),"action_".self::$default_method), self::$args);
                    elseif(TRUE !== method_exists(self::get_self_instance(), "action_".self::$method)):
                        GHelper::trace();
                        $callee = debug_backtrace();
                        GHelper::display_errors(E_USER_ERROR,'Unhandled Exception',
                                                             "Requested  action_".self::$method."() not found in ".self::$controller,$callee[1]['file'],$callee[1]['line'], TRUE);
                    else:
                          try{
                                self::set_view_dir($path);
                                call_user_func_array(array(self::get_self_instance(),"action_".self::$method), self::$args);
                           }catch(Exception $ex) {
                                        throw new Exception("Cannot call the controller ");
                            }
                    endif;
           }

            private static function setselfObject($controller)
                {
                    if(self::$instance == NULL):
                            if (!class_exists($controller, TRUE))
                                     throw new Exception("'Controller name should be combination of filename and controller path name (WelcomeuserAppsController). Please check the user manual.");
                            self::$instance =new $controller();
                    endif;
                }

                public static function get_self_instance()
                {
                     if(self::$instance != NULL)
                         return self::$instance;
                     return NULL;
                }

                public static function get_url_segment($uri = "")
                {
                            $uriarray = array();
                            $uriarray = explode('/',($_SERVER['REQUEST_URI']));

                                if(array_search('index.php',$uriarray))
                                         $index_count = array_search('index.php',$uriarray);
                                elseif(array_search('index.php',$uriarray))
                           // var_dump($uriarray);
                            $uriarray[$indexCount+$uri];
                           return @$uriarray[$indexCount+$uri];
                }

                private static function set_view_dir($path)
                {
                    if (is_dir($path) === FALSE)
                            mkdir($path,0777);

                    return chmod($path,0777);
                }
    }