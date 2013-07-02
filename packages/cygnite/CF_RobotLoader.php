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
 *@package                         :  Packages
 *@subpackages                :  Base
 *@filename                        :  CF_RobotLoader
 *@description                    : This is registry auto loader for CF
 *@author                           : Sanjoy Dey
 *@copyright                     :  Copyright (c) 2013 - 2014,
 *@link	                   :  http://www.appsntech.com
 *@since	                  :  Version 1.0
 *@filesource
 *@warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

/*
function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}

*/

     class CF_RobotLoader
    {
        public static $_classnames = array(
                                        'CF_RequestHandler' => 'packages>cygnite>base>CF_RequestHandler.php',
                                        'CF_Pdf' => 'packages>cygnite>libraries>CF_Pdf.php',
                                        'CF_Encrypt' => 'packages>cygnite>libraries>CF_Encrypt.php',
                                        'CF_Parser' => 'packages>cygnite>libraries>CF_Parser.php',
                                        'CF_ErrorHandler' => 'packages>cygnite>libraries>CF_ErrorHandler.php',
                                        'CF_Security' => 'packages>cygnite>libraries>CF_Security.php',
                                        'CF_FileUpload' => 'packages>cygnite>libraries>CF_FileUpload.php',
                                        'CF_HTTPRequest' => 'packages>cygnite>libraries>CF_HTTPRequest.php', 
                                       'CF_HTMLForm' => 'packages>cygnite>libraries>CF_HTMLForm.php',
                                       'CF_Mailer' => 'packages>cygnite>libraries>CF_Mailer.php',
                                       'CF_Zip' => 'packages>cygnite>libraries>CF_Zip.php',
                                       'CF_Cookie' => 'packages>cygnite>libraries>globals>CF_Cookie.php',
                                       'CF_Files' => 'packages>cygnite>libraries>globals>CF_Files.php',
                                       'CF_Get' => 'packages>cygnite>libraries>globals>CF_Get.php',
                                       'CF_Post' => 'packages>cygnite>libraries>globals>CF_Post.php',
                                       'CF_Request' => 'packages>cygnite>libraries>globals>CF_Request.php',
                                       'CF_Server' => 'packages>cygnite>libraries>globals>CF_Server.php',
                                       'CF_Session' => 'packages>cygnite>libraries>globals>CF_Session.php',
                                       'CF_SecureData' => 'packages>cygnite>libraries>globals>CF_SecureData.php',
                                       'CF_Globals' => 'packages>cygnite>libraries>globals>CF_Globals.php',
                                       'CF_DBConnector'=>'packages>cygnite>database>CF_DBConnector.php',
                                       'CF_ActiveRecordsModel'=>'packages>cygnite>database>CF_ActiveRecordsModel.php',
                                       'CF_GenericQueryBuilder'=>'packages>cygnite>database>CF_GenericQueryBuilder.php',
                                    /*'CF_Apc_Driver' => 'packages>cygnite>libraries>cache>handler>CF_Apc_Driver.php',
                                       'CF_Memcache_Driver' => 'packages>cygnite>libraries>cache>handler>CF_Memcache_Driver.php', */

        );

        protected function __construct()
        {
               spl_autoload_register(array($this, 'auto_loader'));
        }

        private function auto_loader($classname)
        {
                    if(array_key_exists($classname, self::$_classnames)):
                           $includepath = str_replace('>','/',self::$_classnames[$classname]);
                            include_once $includepath;
                    else:
                           trigger_error("Error occured while loading class $classname",E_USER_WARNING);
                    endif;

        }

        public function __call($name, $args)
        {
                if($name !== 'register_classess')
                        trigger_error("Invalid method $name",E_USER_WARNING);

                  call_user_func_array(array($this,'setclass'),$args);
        }

        private function setclass($args)
        {
            switch ($args) :
                    case is_array($args):
                                        foreach($args as $key =>$value):
                                                  self::$_classnames[$key] =str_replace('>','/',$value);
                                        endforeach;
                        break;
                    default:
                                    self::$_classnames[@$args[0]] =str_replace('>','/',@$args[1]);
                        break;
            endswitch;
        }

        protected function request($key,$val=NULL)
        {
            $class = $libpath = "";
            $class = 'CF_'.trim($key);

             if(!array_key_exists($class,self::$_classnames))
                    throw new Exception("Requested $class Library not exists !!");

             $libpath = str_replace('>','/',self::$_classnames[$class]);
             if(is_readable($libpath) && class_exists($class))
                          return new $class($val);

        }
    }
//set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), './libs', './controllers', './models')));
//spl_autoload_register();
