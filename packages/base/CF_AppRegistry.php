<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
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
 * @Filename                       :  CF_AppRegistry
 * @Description                   : This is registry auto loader for CF
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */


    abstract class CF_AppRegistry
    {
                private static $classNames = array();
                var $name;
                public static $instance = NULL;
                private static $value = array();
                private static $_directorypath = array();
              //  private static $_core_dirs = array( 'core/common', 'core/loader', 'core/library', 'core/helpers');
             //  private static $_core_classes = array('cf_Profiler','cf_ErrorHandler','cf_Uri','cf_Security','CF_CONFIG');


             /**
                 * Store the filename (sans extension) & full path of all ".php" files found
                */
                 public static function register_dir($dirs)
                {
                            switch($dirs):
                            case is_array($dirs):
                                                foreach($dirs as $key=>$val)
                                                        self::scan_and_register_objects($val);
                                     break;
                            case is_string($dirs):
                                                       self::scan_and_register_objects($dirs);
                                    break;
                            endswitch;
                }

                public static function register_library_class($className, $fileName)
                {
                        self::$classNames[$className] = $fileName;
                }

                public static function load_lib_class($class_names,$encryptionkey = NULL)
                {
                    switch ($class_names):
                                case is_array($class_names):
                                                        foreach($class_names as $key=>$class_name): //var_dump(self::$classNames[$class_name]);
                                                                  if (isset(self::$classNames[$class_name])):
                                                                        include_once(self::$classNames[$class_name]);
                                                                            self::lib_array_Iterator($class_name,$encryptionkey);
                                                                endif;
                                                        endforeach;

                                    break;
                                case is_string($class_names):
                                            if (isset(self::$classNames[$class_names])):
                                                //show(self::$classNames);
                                                //echo $class_names;
                                                 if(is_readable(self::$classNames[$class_names]))
                                                      include_once self::$classNames[$class_names];
                                                 else
                                                      throw new Exception("Class $class_names cannot load ");

                                                  if(class_exists($class_names)):
                                                         $classname =  new $class_names('ens');
                                                         // self::$instance = self::$classNames[$className];
                                                         $obname  = strtolower(str_replace('CF_', '', $class_names));
                                                         self::store($obname,$classname);
                                                  else:
                                                          throw new Exception("Class $class_names cann't initiated ");
                                                  endif;
                                           else:
                                               GHelper::trace();
                                               $callee = debug_backtrace();
                                               GHelper::display_errors(E_USER_ERROR, 'Unhandled Exception',"Requested CF_".trim($key)." library doesn't exists ", $callee[1]['file'],$callee[1]['line'],TRUE);
                                           endif;

                                    break;
                    endswitch;
                 }

                 private static function lib_array_Iterator($class_name,$encryptionkey)
                 {
                        if(class_exists($class_name)):
                                if($class_name != 'Encrypt' && $encryptionkey ==""):
                                         $classname =  new $class_name();
                                else:
                                        $classname =  new $class_name($encryptionkey);
                                endif;
                                $obname  = strtolower(str_replace('CF_', '', $class_name));
                                self::store($obname,$classname);
                         endif;
                 }

                  public static function load($key)
                 {
                        $obname = NULL;
                        $obname =  strtolower($key);
                        if(!array_key_exists($obname,self::$value)):
                             $callee = debug_backtrace();
                             GHelper::display_errors(E_USER_ERROR, 'Unhandled Exception',"Requested CF_$key library doesn't exists ", $callee[1]['file'],$callee[1]['line'],TRUE);
                        endif;

                        return self::$value[$obname];
                 }

                 public static function  import($dir_name,$filename,$path,$sub_dir = NULL)
                 {
                      if(is_null($dir_name) || $dir_name == "")
                                throw new Exception("Empty directory name passed on ".__METHOD__);
                      if(is_null($filename) || $filename == "")
                                throw new Exception("Empty file name passed on ".__METHOD__);
                      if(is_null($path) || $path == "")
                                throw new Exception("Empty path passed on ".__METHOD__);

                                 switch($path):
                                              case CF_SYSTEM:
                                                                            $prefix = NULL;
                                                                            $path = getcwd().DS.CF_SYSTEM;
                                                                            $prefix = ($dir_name  === 'database') ? DATABASE_PREFIX : FRAMEWORK_PREFIX ;
                                                                            $_directorypath  =    $path.DS.$dir_name.DS.$prefix.$filename.EXT; 

                                                                            if(is_readable($_directorypath) && file_exists($_directorypath))
                                                                                      include_once $_directorypath;
                                                                            else
                                                                                     echo "File Doesn't exist in following path $_directorypath ".__METHOD__;
                                                          break;
                                              case APPPATH:
                                                                            $path =  getcwd().DS.str_replace('/', '', APPPATH);
                                                                            if($sub_dir != NULL)
                                                                                    self::locate_apppath($path,$dir_name,$subdir,$filename);
                                                                            else
                                                                                    self::locate_apppath($path,$dir_name,$subdir = NULL,$filename);
                                               break;
                                 endswitch;
                     return TRUE;
                 }

                 private static function locate_apppath($path,$dir_name,$subdir,$filename)
                 {
                       $subdir =  (!is_null($subdir)) ? $subdir.DS : '';

                         if(is_array($filename)):
                                    foreach($filename as $key =>$value):
                                         $_directorypath[$value]  =    $path.DS.$subdir.$dir_name.DS.$value.EXT;
                                         self::include_file($_directorypath[$value],$value);
                                  endforeach;
                        else:
                                  $_directorypath[$filename]  =    $path.DS.$subdir.$dir_name.DS.$filename.EXT;
                                  self::include_file($_directorypath[$filename],$filename);
                        endif;
                 }

                 private static function include_file($_directorypath,$filename)
                 {
                        $CF_CONFIG = array();
                            try{
                                    if(is_readable($_directorypath) && file_exists($_directorypath)):
                                                 include_once $_directorypath;
                                            self::store(strtolower($filename).'_items', $CF_CONFIG);
                                    endif;
                            }catch(Exception $ex){
                                            echo "Unable to load file $_directorypath ".__METHOD__, $ex->getMessage();
                           }
                }

                 private static function store($name,$values)
                 {
                            self::$value[$name]  = $values;
                 }

                /*
                * Unset All object which unused
                * @param array()
                * @unset objects
                */
                public function unsetobjects($objArray = array())
                {
                            foreach($objArray as $key=>$objvars):
                                         unset($objArray[$key]);
                          endforeach;
                }

                 private static function scan_and_register_objects($val)
               {
                            foreach(scandir($val) as $key=>$value):
                                   if($value!="." && $value !=".."):
                                        $file_name = $value;
                                        $value = $val.DIRECTORY_SEPARATOR.$value;
                                          if(is_dir($value)):
                                                self::register_dir($value);
                                          elseif(is_file($value) && substr($value, -4) === '.php'):
                                                 self::register_library_class(substr($file_name, 0, -4),$value);
                                          endif;
                                   endif;
                            endforeach;
                }

                function scan_dir($base_dir)
                {
                          $directories = array();
                              foreach(scandir($base_dir) as $file):
                                     if($file == '.' || $file == '..') continue;
                                            $dir = $base_dir.DIRECTORY_SEPARATOR.$file;
                                            if(is_dir($dir)) :
                                                $directories []= $dir;
                                                $directories = array_merge($directories, expandDirectories($dir));
                                            endif;
                                endforeach;
                          return $directories;
               }

              /**
                * @warning  You can´t change this!
                *
                */
               public static function copyright()
              {

                     $copyright =    " <div style='padding: 20px 20px 12px;'>
                                          <span style='color:brown;font-weight:bold;margin:2px; padding: 3px; '>  Author:  </span> Cygnite Dev Team - <a  href='http://appsntech.com' >http://appsntech.com </a> - <br>
                                          <p style='color:brown;font-weight:bold;margin:2px; padding: 3px; '>   Create Date:  08-05-2012  </p>
                                          <p style='color:brown;font-weight:bold;margin:2px; padding: 3px; '>   Version   :    ".CF_VERSION." </p>
                                          <p style='margin:2px; padding: 3px; color:brown;font-weight:bold;'>   License:   </p>
                                            </div>";
                          return $copyright;
                }
}