<?php //namespace Cygnite;
if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
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
 *@package                  :  Packages
 *@subpackages              :  Base
 *@filename                 :  CF_RobotLoader
 *@description              : This is registry auto loader for CF
 *@author                   : Sanjoy Dey
 *@copyright                :  Copyright (c) 2013 - 2014,
 *@link	                  :  http://www.cygniteframework.com
 *@since	                  :  Version 1.0
 *@filesource
 *@warning                  :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

     class CFRobotLoader
    {
         /*
          * ----------------------------------------------------------
          * Register all core classes to Cygnite Robot Engine
          * ----------------------------------------------------------
          * Never Manually change any core classes in this section
          * If you would like to add new class into our Robot Engine
          * then you can just register it on autoload libraries which will
          * be added dynamically to Engine else send me patches will
          * update core class.
          *
          */
        public static $_classnames = array(
                                       //'CF_RequestHandler' => 'packages>cygnite>base>CF_RequestHandler.php',
                                       'CF_UserRequest' => 'packages>cygnite>base>CF_UserRequest.php',
                                       'CF_Router' => 'packages>cygnite>base>CF_Router.php',
                                       'CF_IRouter' => 'packages>cygnite>base>CF_IRouter.php',
                                       'CF_ActiveRecords'=>'packages>cygnite>database>CF_ActiveRecords.php',
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
                                        'CF_IRegistry'=> 'packages>cygnite>loader>CF_IRegistry.php',
                                       'CF_AppRegistry'=> 'packages>cygnite>loader>CF_AppRegistry.php',
                                       'CF_BaseController'=> 'packages>cygnite>loader>CF_BaseController.php',
                                       'CF_AppLoader'=> 'packages>cygnite>loader>CF_AppLoader.php',
                                       'CF_SecureData' => 'packages>cygnite>libraries>globals>CF_SecureData.php',
                                       'CF_Globals' => 'packages>cygnite>libraries>globals>CF_Globals.php',
                                       'CF_BaseModel' => 'packages>cygnite>sparker>CF_BaseModel.php',
                                       'CF_View' => 'packages>cygnite>sparker>CF_View.php',
                                       'CF_IActiveRecords' => 'packages>cygnite>database>CF_IActiveRecords.php',
                                       'CF_DBConnector'=>'packages>cygnite>database>CF_DBConnector.php',
                                       'CF_SQLUtilities' =>'packages>cygnite>database>CF_SQLUtilities.php',
                                       'CF_IMemoryStorage' => 'packages>cygnite>libraries>cache>handler>CF_IMemoryStorage.php',
                                       'CF_Apc_Driver' => 'packages>cygnite>libraries>cache>handler>CF_Apc_Driver.php',
                                       'CF_Memcache_Driver' => 'packages>cygnite>libraries>cache>handler>CF_Memcache_Driver.php',

        );

        protected function __construct()
        {
               set_include_path(get_include_path().DS.str_replace('/','',APPPATH));
               set_include_path(get_include_path().DS.CF_SYSTEM);
               spl_autoload_unregister(array($this,'autoloader'));
               spl_autoload_extensions(".php");
               spl_autoload_register(array($this, 'autoloader'));
        }
        /**
        * ----------------------------------------------------------
        * Autoload all classes
        * ----------------------------------------------------------
        * All classes will get autoloaded here.
        *@param $classname string
        *@return void
        *
        */
        private function autoloader($classname)
        {
                if(array_key_exists($classname, self::$_classnames)):
                       $includepath = str_replace('>','/',self::$_classnames[$classname]);
                        return include_once $includepath;
                else:
                       $callee = debug_backtrace();
                       GHelper::trace();
                       GHelper::display_errors(E_USER_ERROR, 'Error Occurred ',"Error occured while loading class $classname", $callee[1]['file'],$callee[1]['line'],TRUE );
                endif;
        }

        /*
         * Call magic method to register classes and models dynamically into cygnite engine
         *@param $name method name
         * @args    $args   array passed into the method
         */
        public function __call($name, $args)
        {
              if($name == 'register_classes' || $name == 'register_models')
                    call_user_func_array(array($this,'setclass'),$args);
              else
                        trigger_error("Invalid method $name",E_USER_WARNING);
        }
      /**
        * ----------------------------------------------------------
        * Set classes dynamically
        * ----------------------------------------------------------
        * Set classes dynamically to Robot engine
        *
        *@param $args unknown
        *@return void
        *
        */
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
         /**
        * ----------------------------------------------------------
        * Request a object of the class
        * ----------------------------------------------------------
        * This method is used to request classes from
        * Cygnite Engine
        *@param $key string
        * @param $val NULL
        *@return object
        *
        */
        protected function request($key,$val=NULL)
        {
            $class = $libpath = "";
            $class = 'CF_'.trim($key);

             if(!array_key_exists($class,self::$_classnames))
                    throw new Exception("Requested $class Library not exists !!");

             $libpath = str_replace('>','/',self::$_classnames[$class]);
             if(is_readable($libpath) && class_exists($class))
                 return new $class($val);
             else
                 trigger_error("Path not readable $libpath");
        }
      /**
        * ------------------------------------------------------------------------------------------
        * Get all loaded classes
        * -----------------------------------------------------------------------------------------
        * This method is used to return all registered class names from cygnite robot
        *@return array
        */
        public function loaded_classes()
        {
            return self::$_classnames;
        }

      /**
        * ------------------------------------------------------------------------------------------
        * Load models and return model object
        * -----------------------------------------------------------------------------------------
        * This method is used to load your all models dynamically
        *@param $key string
        *@param $val string
        *@return object
        */
         protected function models($key,$val=NULL)
        {
            $class = $libpath = "";
            $class = ucfirst(trim($key));

             if(!array_key_exists($class,self::$_classnames))
                    throw new Exception("Requested $class Library not exists !!");

             $libpath = str_replace('/','',APPPATH).DS.'models'.DS;
             if(is_readable($libpath) && class_exists($class))
                 return new $class($val);
             else
                 trigger_error("Path not readable $libpath");
        }
    }
//set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), './libs', './controllers', './models')));
