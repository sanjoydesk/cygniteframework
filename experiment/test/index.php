<?php
namespace Cyg;
use Cyg\Libs;
use Cyg\Helpers;
use Cyg\Load;

//include 'namespaces/second.php';
//include 'namespaces/third.php';

class CFRobotLoader
{

    private static $coreClasses = array(
                                  'Second'=>'Cyg\\Core\\Second',
                                  'Third'=>'Cyg\\Libs\\Third',
                                  'Assets'=>'Cyg\\Helpers\\Assets',
                                  'Loader'=>'Cyg\\Load\\Loader',
    );
    private static $instance = array();


    protected function __construct()
    {
        spl_autoload_register(array($this,'load'));

    }

    public function load($className)
    {
        $resultstring = function($string){
                   return strtolower($string);
        };
           $coreclasses = array_flip(self::$coreClasses);

           if(array_key_exists($coreclasses[$className], self::$coreClasses)){
                   $includepath = $resultstring(str_replace('\\','/',self::$coreClasses[ucfirst($coreclasses[$className])])).'.php';
               return include $includepath;
           }

    }

    public function __get($key)
    {
            if(!isset(self::$instance[$key]))
                   self::$instance[$key] = new self::$coreClasses[$key];
       return self::$instance[$key];
    }


}

//$engine = new Engine();

class Cygnite extends CFRobotLoader
{
    static private $selfinstance;
    /*public function __construct() {
        var_dump($this);exit;
        parent::__construct();

    } */
     public static function loader()
    {
         if(is_null(self::$selfinstance))
                 self::$selfinstance = new self();
        return self::$selfinstance;
    }
}

//var_dump(Cygnite::loader());//->Second->set(); echo "<br>";
Cygnite::loader()->Second->set(); echo "<br>";
Cygnite::loader()->Third->set(); echo "<br>";
Cygnite::loader()->Assets->set(); echo "<br>";
Cygnite::loader()->Loader->set(); echo "<br>";

        exit;

//$engine->Second->set(); echo "<br>";
//$engine->Third->set(); echo "<br>";
//$engine->Assets->set(); echo "<br>";
//$engine->Loader->set(); echo "<br>";




/*
     define('MY_BASE_PATH', (string) (__DIR__ . '/'));
            // Set include path
$path = (string) get_include_path();
$path .= (string) (PATH_SEPARATOR . MY_BASE_PATH . 'php_class/');
$path .= (string) (PATH_SEPARATOR . MY_BASE_PATH . 'php_global_class/');
// $path .= (string) (PATH_SEPARATOR . 'additional/path/');
set_include_path($path);

spl_autoload_register(function ($className) {
            $className = (string) str_replace('\\', DIRECTORY_SEPARATOR, $className);
            include_once($className . '.class.php');
        }); */


