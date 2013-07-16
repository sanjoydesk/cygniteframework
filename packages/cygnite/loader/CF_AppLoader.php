<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :   Loader
 * @Filename                       :  CF_Apploader
 * @Description                   :  This file act as application files loader. And Connecting DB with Model
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class CF_AppLoader extends CF_AppRegistry implements CF_IRegistry
{
              var $directory = NULL;
              var $values =array();
              private $data = array();

            public function __construct($registry = array()) {}

            //prevent clone.
            public function __clone(){}


            public function __get($key)
            {
                    if (isset($this->data[$key]))
                        return $this->data[$key];
            }

            public function __set($key, $value)
            {
                    $this->data[$key] = $value;
            }

             /*
            * This function is to load requested model file
            * @param string (model name)
            * @return void
            */
             public function model($model_name)
             {
                         switch($model_name):
                                    case is_array($model_name):
                                            $i = 0;
                                            $count = count($model_name);
                                                    while($i < $count):
                                                        $this->_model($model_name[$i]);
                                                        $i ++;
                                                    endwhile;
                                          break;
                                    default :
                                                    $this->_model($model_name);
                                        break;
                        endswitch;
            }

            private function _model($model_name)
            {
                        $this->directory = str_replace('/', '', APPPATH).DS."models".DS;
                        if(is_readable($this->directory.$model_name.EXT)):
                            require_once $this->directory.$model_name.EXT;
                            //$modelname = $this->make_model($model_name);
                            $modelname = ucfirst($model_name);
                            if (empty($this->data[$model_name]))
                                    $modelobj = new $modelname();
                            $this->__set($model_name, $modelobj);
                            unset($model_name);unset($modelobj);
                        else:
                            $callee = debug_backtrace();
                            GHelper::trace();
                            GHelper::display_errors(E_USER_ERROR, 'Error Occurred ','Unable to load requested model  '.$model_name.EXT, $callee[0]['file'],$callee[0]['line'],TRUE );
                        endif;
            }

              private function make_model($modelname)
             {
                    if(!empty($modelname))
                             $std_model_name = ucfirst($modelname).'_'.ucfirst(str_replace('/', '',APPPATH)).'Model';
                    return $std_model_name;
             }

       /*
         *  Load Helper file
         *
         */
          public   function helper($file_name,$prefix = FRAMEWORK_PREFIX)
          {
                $helpers_dir = CF_SYSTEM.DS.'cygnite'.DS."helpers".DS;
                $app_helper_dir = str_replace('/','',APPPATH).DS.'helpers';

                if(file_exists($helpers_dir.$prefix.$file_name.EXT) && is_readable($helpers_dir.$prefix.$file_name.EXT)):
                    include($helpers_dir.$prefix.$file_name.EXT);
                elseif(file_exists($app_helper_dir.DS.$prefix.$file_name.EXT) && is_readable($app_helper_dir.DS.'helpers'.DS.$prefix.$file_name.EXT)):
                    include($app_helper_dir.$prefix.$file_name.EXT);
                else:
                    $callee = debug_backtrace();
                    GHelper::trace();
                    GHelper::display_errors(E_USER_ERROR, 'Error Occurred',"Unable to load requested helper ".$file_name, $callee[0]['file'],$callee[0]['line'],TRUE );
                    endif;
                unset($file_name);
        }

        public function __destruct()
        {
            unset($this->directory); unset($this->values);
          }
}