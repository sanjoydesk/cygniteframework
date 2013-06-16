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
 * @Filename                       :  CFView
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

     class CFView
    {
              var $directory = NULL;
              var $view_path = NULL;
              var $template,$values =array();
              public $requestedcontroller;
              public $model = NULL;
              private static $name = array();
              private static $uicontent,$content;


              /*
            * This function is to load requested view file
            * @param string (view name)
            *
            */
          public  function render($view,$ui_content =NULL)
          {
                            $trace = debug_backtrace();
                            $controller = strtolower(str_replace('AppsController','',$trace[1]['class']));
                            $path= str_replace('/', '', APPPATH).DS.'views'.DS.$controller.DS;

                            if(!file_exists($path.$view.'.view'.EXT) || !is_readable($path.$view.'.view'.EXT)):
                                 GHelper::trace();
                                GHelper::display_errors(E_USER_ERROR, 'Error rendering view page ',"Can not find view page on ".$path.$view.'.view'.EXT, $trace[0]['file'],$trace[0]['line'],TRUE );
                            endif;

                            self::$name[strtolower($view)] = $view;
                            if(is_readable($path.self::$name[$view].'.view'.EXT)) :
                                        ob_start();
                                            if($ui_content== TRUE):
                                                      self::$uicontent =$ui_content;
                                                      $this->view_path = $path.self::$name[$view].'.view'.EXT;
                                                      $this->loadview();
                                                      return self::$content;
                                            endif;
                                         $this->view_path = $path.self::$name[$view].'.view'.EXT;
                                         return $this;
                            endif;

          }

          protected function createsections(array $sections)
          {
                foreach($sections as $key=>$value):
                    $this->template[$key] = $value;
                endforeach;
          }

          public function setlayout($layout, array $params)
          {
              $trace = debug_backtrace();
              $this->requestedcontroller = strtolower(str_replace('AppsController','',$trace[1]['class']));
              $this->layoutparams = $params;
              $path= str_replace('/', '', APPPATH).DS.'views'.DS.$this->requestedcontroller.DS;
               self::$name[strtolower($layout)] = $layout;
               if(is_readable($path.rtrim(self::$name[strtolower($layout)].'.layout').EXT)):
                     ob_start();
                     $this->view_path = $path.self::$name[strtolower($layout)].'.layout'.EXT;
                     unset(self::$name[strtolower($layout)]);
               endif;
               $this->loadview();

          }

          public function renderlayout($page)
          {
              $path= str_replace('/', '', APPPATH).DS.'views'.DS.$this->requestedcontroller.DS.$page.EXT;
              ob_start();
              try{
                  include_once $path;
              }catch(Exception $ex) {
                  throw $ex;
              }
              $this->output_buffer();
          }

             public function with($arr_values)
             {
                    if(is_array($arr_values))
                              $this->values = (array) $arr_values;
                    $this->loadview();
             }

             private function loadview()
             {
                 try{
                    include $this->view_path;
                }catch(Exception $ex){
                    echo $ex->getMessage();
                }
                  $this->output_buffer();
             }

             private function output_buffer()
             {
                 $output=ob_get_contents();
                           ob_get_clean();

                if(isset(self::$uicontent) && self::$uicontent ==TRUE)
                        self::$content =  $output;
                else
                      echo $output;
                ob_end_flush();
             }

             public function __destruct()
             {
                 unset($this->values);
             }
    }