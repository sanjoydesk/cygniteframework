<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
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
 * @Sub Packages               :   Loader
 * @Filename                       :  CF_Apploader
 * @Description                   :  This file act as application files loader. And Connecting DB with Model
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
   Cygnite::import(CF_SYSTEM,'database', 'Connect');
   Cygnite::import(CF_SYSTEM,'loader', 'AppRegistry');
   include_once CF_SYSTEM.DS.'cygnite'.DS.'sparker'.DS.'CFModel'.EXT;

class CF_AppLoader extends CF_AppRegistry implements IRegistry
{
                      var $directory = NULL;
                      var $view_path = NULL;
                      var $values =array();
                      private $data = array();
                      protected $loaded = NULL;
                      public $model = NULL;
                      private static $name = array();
                      private static $uicontent;

                      public function __construct($registry = array())
                    {
                            $expires = 60*60*24*14;
                            header("Pragma: public");
                            header("Cache-Control: maxage=".$expires);
                            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

                   }

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
                                    $modelname = $this->make_model($model_name);
                                    if (empty($this->data[$model_name]))
                                            $modelobj = new $modelname();

                                    $this->_set_object($model_name, $modelobj);
                                    unset($model_name);unset($modelobj);
                                else:
                                    $callee = debug_backtrace();
                                    GHelper::display_errors(E_USER_ERROR, 'Error Occurred ','Unable to load requested model  '.$model_name.EXT, $callee[0]['file'],$callee[0]['line'],TRUE );
                                endif;
                    }

                    private function _set_object($key,$value)
                    {
                            $this->data[$key] = $value;

                    }

                     private function make_model($modelname)
                     {
                            if(!empty($modelname))
                                     $std_model_name = ucfirst($modelname).'_'.ucfirst(str_replace('/', '',APPPATH)).'Model';
                            return $std_model_name;
                     }

                    function print_gzipped_output()
                    {
                            $HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
                            if(headers_sent())
                                $encoding = false;
                            else if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false )
                                $encoding = 'x-gzip';
                            else if( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false )
                                $encoding = 'gzip';
                            else
                                $encoding = false;

                            if( $encoding ):
                                    $contents = ob_get_clean();
                                    $_temp = strlen($contents);
                                    if ($_temp < 2048) :   // no need to waste resources in compressing very little data
                                                print($contents);
                                    else:
                                                header('Content-Encoding: '.$encoding);
                                                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                                                $contents = gzcompress($contents, 9);
                                                $contents = substr($contents, 0, $_temp);
                                                print($contents);
                                    endif;
                            else:
                                ob_end_flush();
                            endif;
                    }

                     public function request($key)
                     {
                       if(!is_null($key) || $key != ""):
                              try{
                                 Cygnite::loader()->request($key);
                              }catch(Exception $ex){ echo "here exception";
                                    echo $ex->getMessage();
                              }
                                 return (FALSE != Cygnite::loader()->request($key)) ? Cygnite::loader()->request($key)
                                                        : GHelper::trace();
                       else:
                             $callee = debug_backtrace();
                             GHelper::display_errors(E_USER_ERROR, 'Error Occurred',"Unable to load requested library ".$key, $callee[0]['file'],$callee[0]['line'],TRUE );
                             show(debug_print_backtrace());
                       endif;
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
                            include_once($helpers_dir.$prefix.$file_name.EXT);
                        elseif(file_exists($app_helper_dir.DS.$prefix.$file_name.EXT) && is_readable($app_helper_dir.DS.'helpers'.DS.$prefix.$file_name.EXT)):
                            include_once($app_helper_dir.$prefix.$file_name.EXT);
                        else:
                            $callee = debug_backtrace();
                            GHelper::trace();
                            GHelper::display_errors(E_USER_ERROR, 'Error Occurred',"Unable to load requested helper ".$file_name, $callee[0]['file'],$callee[0]['line'],TRUE );
                            endif;
                        unset($file_name);
                }

                public function __destruct()
                {
                        ob_end_flush(); //ob_end_clean();
                        ob_get_flush();
                        unset($this->directory); unset($this->values);unset($this->view_path);
                  }
}