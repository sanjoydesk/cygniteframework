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
//namespace common\cf_Uri;
 //AppLogger::write_error_log(' URI Initialized',__FILE__);
    CF_AppRegistry::import('base', 'Dispatcher',CF_BASEPATH);
    CF_AppRegistry::import('base', 'RouteMapper',CF_BASEPATH);

    class CF_Uri
    {
            private $index_page = "index.php";
            var $values =  NULL;
            private  $router_enabled = FALSE;


            function __construct()
            {       try {
                         //   AppLogger::write_error_log(__CLASS__.' Initialized',__FILE__);
                    } catch(Exception $ex){
                            $ex->getMessage();
                    }
                }

            private function _is_enabled()
            {
                try{
                            include_once APPPATH.'routerconfig'.EXT;
                       }  catch (Exception $excep) {
                            $excep->getMessage();
                       }
                     if(TRUE=== Router::$is_router_enabled):
                            $this->router_enabled = TRUE;
                            return TRUE;
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
                                  GlobalHelper::display_errors(E_USER_WARNING, 'Bad Url Format ', 'You are not allowed to pass query string in url.', __FILE__,$callee[0]['line'] ,TRUE);

                       //var_dump($expression);
                      //echo $find_index;
                      $expression = array_filter(explode('/',($_SERVER['REQUEST_URI'])));
                      if($this->_is_enabled())
                               $router = Router::get_route();

                      $segment = explode('/', $router['url']);

                    $find_index = array_search($this->index_page,$expression);

                          if($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] == $segment[0] && $expression[4] == $segment[1])):
                                // echo "here I am with routing and index page and match all uri <br>";
                                 RouteMapper::route($router['url'],$router['route_to']);
                          elseif($find_index != '' && TRUE == $this->_is_enabled()  && ($expression[3] != $segment[0] && $expression[4] != $segment[1])):
                                 //echo "here I am with routing and index page but not match uri<br>";
                                Dispatcher::response_user_request($expression, $find_index);
                         elseif($find_index == '' && ($expression[2] == $segment[0] && $expression[3] == $segment[1]) ):
                                //echo "here m nw elseif";
                                RouteMapper::route($router['url'], $router['route_to']);
                        elseif($find_index == '' && TRUE == $this->_is_enabled() && ($expression[2] != $segment[0] && $expression[3] != $segment[1])):
                            // echo "here I am with routing and without index page <br>";
                             $find_indexcount = array_search(ROOT_DIR,$expression);
                            Dispatcher::response_user_request($expression, $find_indexcount);
                            //   RouteMapper::route($segment[0], $segment[1]);
                       else: //echo "jhhjhj";
                           RouteMapper::route($router['url'],$router['route_to']);
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
                        $CF_CONFIG = CF_AppRegistry::load('Config')->get_config_items('config_items');
                        if($CF_CONFIG['GLOBAL_CONFIG']['enable_profiling']==TRUE)
                           CF_AppRegistry::load('Profiler')->end_profiling();
                       unset($this->index_page);
                }
    }
