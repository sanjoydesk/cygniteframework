<?php
/*
 * ===========================================================================
 *  An open source application development framework for PHP 5.1.6 or newer
 *
 * Load class is to load requested database and view files
 * @access public
 * @Author          :         Sanjoy Dey
 * @Modified By :
 * @Warning      :         Any changes in this file can cause abnormal behaviour of the framework
 * @Developed By  :         PHP-ignite Team
 * ===========================================================================
 */

require CF_BASEPATH.DS.'loader'.DS.'IRegistry'.EXT;
class CF_AppLibraryRegistry implements IRegistry
{
        private static $_register_dir = array();
        private static $_library = NULL;
        private static $_helpers = NULL;
        private static $_plugins = NULL;
        private static $_models = NULL;

        public static function initialize($dirRegistry = array())
        {
             self::$_register_dir = $dirRegistry;
             $auto_load_apps_dirs = array();
            $plugins_dir = WEB_ROOT.OS_PATH_SEPERATOR.str_replace('/', '',APPPATH).OS_PATH_SEPERATOR.'vendors'.OS_PATH_SEPERATOR."plugins";
            $library_dir = WEB_ROOT.OS_PATH_SEPERATOR.str_replace('/', '',APPPATH).OS_PATH_SEPERATOR.'vendors'.OS_PATH_SEPERATOR."libs";
            $helpers_dir = WEB_ROOT.OS_PATH_SEPERATOR.str_replace('/', '',APPPATH).OS_PATH_SEPERATOR.'vendors'.OS_PATH_SEPERATOR."helpers";

           // is_dir($directoryPath) or mkdir($directoryPath, 0777);
            $auto_load_apps_dirs = array($library_dir,$helpers_dir,$plugins_dir);

            self::$_library =   self::$_register_dir['libraries'];
            self::$_helpers = self::$_register_dir['helpers'];
            self::$_plugins = self::$_register_dir['plugins'];
            self::$_models = self::$_register_dir['model'];

             // Register all framework core directories to include core files
            CF_AppRegistry::register_dir($auto_load_apps_dirs);
            if(!empty(self::$_library)):
                      // Auto Load all framework core classes
                      CF_AppRegistry::load_lib_class(self::$_library);
            endif;

             if(!empty(self::$_helpers)):
                      CF_AppRegistry::import('helpers',self::$_helpers,APPPATH,'vendors');
            endif;

             if(!empty(self::$_plugins)):
                      CF_AppRegistry::load_lib_class(self::$_plugins);
            endif;

             if(!empty(self::$_models)):
                      // Auto Load all framework core classes
                      CF_AppRegistry::load_lib_class(self::$_models);
            endif;
        }

        public function apps_load($key)
        {
                if($key != NULL || $key != "")
                        return CF_AppRegistry::load($key);
                else
                        throw new ErrorException ("Invalid argument passed on ".__FUNCTION__);
        }
}