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
 * @Filename                       :  CF_Request
 * @Description                   : This is the base entry point for user request and dispatch to requested controllers.
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 */


      /**
    * ---------------------------------------------------------------------
    * Import Dispatcher to handle and response all user request
    * ---------------------------------------------------------------------
    */
    Cygnite::import(CF_SYSTEM,'base', 'Dispatcher');
    /**
    * ---------------------------------------------------------------------
    * Import RouteMapper to handle application routing
    * ---------------------------------------------------------------------
    */
    Cygnite::import(CF_SYSTEM,'base', 'RouteMapper');

    //namespace base\CF_Uri;
    class CF_RequestHandler
    {

      /**
       * The name of the entry page
       * @var string
       */
        private $index_page = 'index.php';

      /**
       * Define the router variable. default value set as FALSE
       * @var bool
       */
        private  $router_enabled = FALSE;

         /**
        *  This funtion is to calculate the total memory usage by the running script
        *
        * @access	private
        * @param	 none
        *@return boolean value
        */
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

      /**
        * This is to handle all user request and response to user request via dispatcher class
        * @access	public
        *@param none
       * @return void
        */
        public function handle()
        {
               $expression  = $querystring = array();
               $querystring = explode('?',$_SERVER['REQUEST_URI'],2);
               $calee = debug_backtrace();
               //Not allowing user to pass any kind of query string in url
               if(!empty($querystring[1]))
                          GHelper::display_errors(E_USER_WARNING, 'Bad Url Format ', 'You are not allowed to pass query string in url.', __FILE__,$callee[0]['line'] ,TRUE);

              $expression = array_filter(explode('/',($_SERVER['REQUEST_URI'])));

              //Check routing is enable or not
              if($routeconfig = $this->_is_enabled()):
                   $router = $routeconfig;
                    if($routeconfig['url'] =="")
                          throw new Exception ("Invalid url parameter on Route::set_route()");
                    if($routeconfig['routeto'] =="")
                          throw new Exception ("Invalid routeto parameter on Route::set_route()");
               endif;

              $segment = explode('/', $router['url']);

            $find_index = array_search($this->index_page,$expression);

                  //Handle user requests
                  if($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] == $segment[0] && $expression[4] == $segment[1])):
                         RouteMapper::route($router['url'],$router['routeto']);
                  elseif($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] != $segment[0] && $expression[4] != $segment[1])):
                        Dispatcher::response_user_request($expression, $find_index);
                 elseif($find_index == '' && ($expression[2] == $segment[0] && $expression[3] == $segment[1]) ):
                        RouteMapper::route($router['url'], $router['routeto']);
                elseif($find_index == '' && TRUE == $this->_is_enabled() && ($expression[2] != $segment[0] && $expression[3] != $segment[1])):
                     $find_index = array_search(ROOTDIR,$expression);
                    Dispatcher::response_user_request($expression, $find_index);
                elseif($find_index == '' && ($expression[2] == $segment[0] && $expression[3] != $segment[1]) ):
                         Dispatcher::response_user_request($expression, $find_index);
               else:
                        RouteMapper::route($router['url'],$router['routeto']);
                endif;
        }

        /**
         * ---------------------------------------------------------
         * prevent cloning
         * ---------------------------------------------------------
         */
        public function __clone(){}

        /**
         *  This method is used to destroy the global variables of the class
         * @unset variable to release memory
         */
        function __destruct()
        {
                $profiler = Config::getconfig('global_config','enable_profiling');
                if($profiler==TRUE)
                   Profiler::end();
               unset($this->index_page);
        }
    }//End of the class