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

    class Dispatcher
    {

             private static $default_method = "index";
             private static $controller = NULL;
             private static $method = NULL;
             private static $args = array();
             private static $instance = NULL;

            public static function response_user_request($expression,$find_index,$router = FALSE)
            {
                //// var_dump(array_key_exists($find_index+1,$expression)); echo $expression[$find_index+1];
                    $config =  CF_AppRegistry::load('Config')->get_config_items('config_items');
                    $default_controller_name = $config['GLOBAL_CONFIG']['default_controller'];
                     if($find_index && (array_key_exists($find_index+1,$expression)) ===TRUE):
                               self::$controller = ucfirst($expression[$find_index+1]).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                               self::$method = strtolower($expression[$find_index+2]);
                               self::$args = array_slice($expression,$find_index+3);
                               self::import_controller($expression[$find_index+1]);
                               self::call_requested_controller();
                     elseif($find_index && (array_key_exists($find_index+1,$expression)) ===FALSE):
                              self::$controller = ucfirst($default_controller_name).ucfirst(str_replace('/', '',APPPATH)).'Controller';
                              self::$method = strtolower(self::$default_method);
                              self::import_controller($default_controller_name);
                              self::call_requested_controller();
                     elseif(empty($find_index) && is_null(self::get_self_instance())):
                             //echo "If index file not available";
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
                     if(is_readable(str_replace("/",DS,APPPATH)."controllers".DS.strtolower($args[0]).EXT))
                             require_once FPATH.'/'.APPPATH."controllers".DS.strtolower($args[0]).EXT;
                     else
                           exit("<span style='color:#FF0000;'>Error: 404 Page not Found.<br> Requested $args[0] Controller not found. Please check directory permission if controler available</span>");
           }

           private static function call_requested_controller()
           {
                    //var_dump(preg_replace('/[^a-zA-Z0-9]/', '', self::$controller));
                    self::setselfObject(self::$controller);
                    if(empty(self::$method)|| self::$method == ""):
                               call_user_func_array(array(self::get_self_instance(),"action_".self::$default_method), self::$args);
                    elseif(TRUE !== method_exists(self::get_self_instance(), "action_".self::$method)):
                             exit("<span style='color:#FF0000;'>Error: 500  : Undefined method action_".self::$method ." in ".self::$controller."</span>");
                    else:
                                try{
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

                public function get_url_segment($uri = "")
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
    }