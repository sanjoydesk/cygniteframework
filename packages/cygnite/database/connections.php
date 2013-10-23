<?php
namespace Cygnite\Database;

use Cygnite\Database\Configurations;
use PDO;

abstract class Connections
{

    public static $connections = array();

    public static $instance;

    private $_stdObject;

    private static $_config;

    public $connection;

    private $_options = array(
        PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_WARNING,
        PDO::ATTR_CASE              =>  PDO::CASE_LOWER,
        PDO::ATTR_PERSISTENT        =>  true,
        PDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES	=>  false
    );

    public function initialize($connection_name = null)
    {
        Configurations::initialize($connection_name);
    }

    public static function get($key)
    {
         return (isset(static::$connections[$key])) ? static::$connections[$key] : null;
    }

    private static function parseUrl()
    {
        $socketDb = '';

        $urlArgs = func_get_args();
        $url     = @parse_url($urlArgs[0]);

        $info = new \stdClass();

        $info->dbType   = $url['scheme'];
        $info->host     = $url['host'];
        $info->port     = isset($url['port']) ? $url['port'] : '';
        $info->username = isset($url['user']) ? $url['user'] : null;
        $info->password = isset($url['pass']) ? $url['pass'] : null;
        $info->database = isset($url['path']) ? substr($url['path'], 1) : null;
        $info->charset  = (isset($url['query'])) ? str_replace('charset=', '', $url['query']) : '' ;

        if ($info->host == 'unix(') {

            $socketDb =  $info->host . '/' .  $info->database;
            if (preg_match_all('/^unix\((.+)\)\/(.+)$/', $socketDb, $matches) > 0) {

                $info->host = $matches[1][0];
                $info->database = $matches[2][0];

            }
        }

        return  $info;
    }

    public function setConnection($connectionString)
    {
        $info = static::parseUrl($connectionString);

        self::$_config = $info;

        /** @var $info instance of Standard class */
        if ($info instanceof \stdClass) {
              $this->_stdObject = $info;
        }

        $databaseName= str_replace('/', '', $this->_stdObject->database);

        $port =  (is_numeric($this->_stdObject->port))
                    ?
                    ';port='.$this->_stdObject->port
                    : '';

        $dns = str_replace(
            ' ',
            '',
            $this->_stdObject->dbType.':host='.$this->_stdObject->host.$port.';dbname='.$databaseName
        );

        $pass = (isset($this->_stdObject->password) && !is_null($this->_stdObject->password) )
                     ? $this->_stdObject->password : '';

        try {
            if (!array_key_exists($databaseName, static::$connections)) {
                static::$connections[$databaseName] = new PDO(
                    $dns,
                    $this->_stdObject->username,
                    $pass,
                    $this->_options
                );

                //static::$connections[$databaseName]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                static::$connections[$databaseName]->setAttribute(
                    PDO::MYSQL_ATTR_INIT_COMMAND,
                    'SET NAMES '.$this->_stdObject->charset
                );
                static::$connections[$databaseName]->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
                static::$connections[$databaseName]->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE,
                    PDO::FETCH_OBJ
                );

            }

            return static::$connections[$databaseName];

        } catch (\PDOException $e) {
            throw new \Exceptions($e->getMessage());
        }

    }

    public static function getConfiguration()
    {
        if (is_object(static::$_config)) {
            return static::$_config;
        }

        return null;
    }
}