<?php
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
 * @Sub Packages               :   Database
 * @Filename                       :  DB_Connect
 * @Description                   :  This DB library file is used to create DB object by which we can connect PDO connections
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
 require_once 'IDBConnect'.EXT;

 abstract class CF_DBConnect implements IDBConnect
 {

       private static  $conn = array();
        /** Variable array $database_types with all database supported. */
        private static $db_types = array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");
        /** Variable $host, database server address. */
        private static $host = NULL;
        /** Variable $database, database name. */
        private static $database , $dbinstance = NULL;
        /** Variable $user, database user. */
        private static $user = NULL;
        /** Variable $password, database password. */
        private static $password = NULL;
        /** Variable $port, database port only if really necessary. */
        private static $port = NULL;
        /** Variable $database_type, important explicit type. */
        private static $db_type = NULL;
        /** Variable $err_msg, always empty if not have errors. */
        public static $err_msg = "";

        public static function cnXN()
        {
            $DBCONFIG = array();
            $db_config =  Config::getconfig('db_config');

            if(file_exists(APPPATH.'/configs/config'.EXT)) :
                try{
                     foreach($db_config as $row=>$value):
                         self::$db_type = strtolower($value['dbtype']);
                         self::$host = $value['host_name'];
                         self::$database = $value['dbname'];
                         self::$user = $value['username'];
                         self::$password = $value['password'];
                         self::$port = $value['port'];
                         if(!is_null($value['username']) && !is_null($value['dbname'])):
                            self::connect(strtolower(self::$db_type),self::$host,self::$database,self::$user,self::$password,self::$port);
                         endif;
                         //self::$conn[$value['dbname']] = new DB_MySQLAdapter($value['dbtype'].":host=".$value['host_name'].";dbname=".$value['dbname'],$value['username'],$value['password']);
                         // self::$conn[$value['dbname']]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     endforeach;
                } catch(PDOException $ex) {
                        echo "Database connection failed ! ".$ex->getMessage();exit;
                }
            endif;
        }

                public function connect($db_type,$host,$database,$user,$password,$port="")
                {
                        if(in_array($db_type, self::$db_types)):
                        try{
                                switch(strtolower(trim($db_type))):
                                        case "mysql":
                                                self::$conn[$database] = (is_numeric(self::$port) && self::$dbinstance == NULL) ?
                                                                                new DB_MySQLAdapter("mysql:host=".self::$host.";port=".self::$port.";dbname=".self::$database, self::$user, self::$password)
                                                                           : new DB_MySQLAdapter("mysql:host=".self::$host.";dbname=".self::$database, self::$user, self::$password);
                                                            // $this->set_cnXn_obj($database,self::$conn[$database]);
                                                break;
                                        case "mssql":
                                                self::$conn[$database] = new DB_MySQLAdapter("mssql:host=".self::$host.";dbname=".self::$database, self::$user, self::$password);
                                                break;
                                        case "sqlsrv":
                                                self::$conn[$database] = new DB_MySQLAdapter("sqlsrv:server=".self::$host.";database=".self::$database, self::$user, self::$password);
                                                break;
                                        case "ibm": //default port = ?
                                                self::$conn[$database] = new DB_MySQLAdapter("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".self::$database."; HOSTNAME=".self::$host.";PORT=".self::$port.";PROTOCOL=TCcfP;", self::$user, self::$password);
                                                break;
                                        case "dblib": //default port = 10060
                                                self::$conn[$database] = new DB_MySQLAdapter("dblib:host=".self::$host.":".self::$port.";dbname=".self::$database,self::$user,self::$password);
                                                break;
                                        case "odbc":
                                                self::$conn[$database] = new DB_MySQLAdapter("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=".self::$user);
                                                break;
                                        case "oracle":
                                                self::$conn[$database] = new DB_MySQLAdapter("OCI:dbname=".self::$database.";charset=UTF-8", self::$user, self::$password);
                                                break;
                                        case "ifmx":
                                                self::$conn[$database] = new DB_MySQLAdapter("informix:DSN=InformixDB", self::$user, self::$password);
                                                break;
                                        case "fbd":
                                                self::$conn[$database] = new DB_MySQLAdapter("firebird:dbname=".self::$host.":".self::$database, self::$user, self::$password);
                                                break;
                                        case "sqlite2": //ej: "sqlite:/path/to/database.sdb"
                                                self::$conn[$database] = new DB_MySQLAdapter("sqlite:".self::$host);
                                                break;
                                        case "sqlite3":
                                                self::$conn[$database] = new DB_MySQLAdapter("sqlite::memory");
                                                break;
                                        case "pg":
                                                self::$conn[$database] = (is_numeric(self::$port)) ?
                                                                                                    new DB_MySQLAdapter("pgsql:dbname=".self::$database.";port=".self::$port.";host=".self::$host, self::$user, self::$password)
                                                                                                : new DB_MySQLAdapter("pgsql:dbname=".self::$database.";host=".self::$host, self::$user, self::$password);
                                                break;
                                        default:
                                                self::$conn[$database] = null;
                                                break;
                               endswitch;

                                self::$conn[$database]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                                //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                                //$this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY => true);
                                //  $this->set_connection($database,self::$conn[$database]);
                                self::set_cnXn_obj($database,self::$conn[$database]);
                                return  self::$conn[$database];
                        }catch(PDOException $e){
                               echo  self::$err_msg = "Error: ". $e->getMessage();
                                return false;
                        }
                else:
                        self::$err_msg = "Error: Error establishing a database connection (error in params or database not supported).";
                        return false;
                endif;
                }
                /*
                         public function __set($key,$value)
                {
                        self::$conn[$key] = $value;
                }
            */
                public function __get($key)
                {
                           $callee = debug_backtrace();
                        if(isset(self::$conn[$key]))
                                return self::$conn[$key];
                        else
                                 GHelper::display_errors(E_USER_ERROR,"Databse Error Occurred ", " Undefined variable {$key} given for db connection", $callee[0]['file'], $callee[0]['line'],TRUE);
                }

                static function set_cnXn_obj($key,$value)
                {
                        self::$conn[$key] = $value;
                }
                public function get_cnXn_obj($key)
                {
                    $callee = debug_backtrace();
                     if(isset(self::$conn[$key]))
                                return self::$conn[$key];
                     else
                                GHelper::display_errors(E_USER_ERROR,"Databse Error Occurred ", " Db connection error  {$key} ", $callee[0]['file'], $callee[0]['line'],TRUE);
                }
}

CF_AppRegistry::import('database', 'MySQLAdapter',CF_SYSTEM);