<?php
abstract class CF_DBConnector
{
    private $conn = array();
    /** Variable array $database_types with all database supported. */
    private $db_types = array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");
    /** Variable $host, database server address. */
    private $host = NULL;
    /** Variable $database, database name. */
    private $database , $dbinstance = NULL;
    /** Variable $user, database user. */
    private $user = NULL;
    /** Variable $password, database password. */
    private $password = NULL;
    /** Variable $port, database port only if really necessary. */
    private $port = NULL;
    /** Variable $database_type, important explicit type. */
    private $db_type = NULL;
    /** Variable $err_msg, always empty if not have errors. */
    public $err_msg = "";

    public function connect()
    {
        $db_config = array();
        $db_config =  Config::getconfig('db_config');

        if(file_exists(str_replace('/','',APPPATH).DS.'configs'.DS.'database'.EXT)) :
            try{
                 foreach($db_config as $row=>$value):
                     $this->db_type = strtolower($value['dbtype']);
                     $this->host = $value['host_name'];
                     $this->database = $value['dbname'];
                     $this->user = $value['username'];
                     $this->password = $value['password'];
                     $this->port = $value['port'];
                     if(!is_null($value['username']) && !is_null($value['dbname'] && !is_null($value['hostname'])))
                       return $this->driverconnect($this->db_type,$this->database);
                     else
                         throw new Exception("Database configuration error.");

                 endforeach;
            } catch(PDOException $ex) {
                    echo "Database connection failed ! ".$ex->getMessage();exit;
            }
        endif;
    }

    public function driverconnect($db_type,$database)
    {
        if(in_array($db_type, $this->db_types)):
        try{
                switch(strtolower(trim($db_type))):
                        case "mysql":
                                $this->conn[$database] = (is_numeric($this->port) && is_null($this->dbinstance)) ?
                                new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->database, $this->user, $this->password)
                                : new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);

                                break;
                        case "mssql":
                                $this->conn[$database] = new PDO("mssql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
                                break;
                        case "sqlsrv":
                                $this->conn[$database] = new PDO("sqlsrv:server=".$this->host.";database=".$this->database, $this->user, $this->password);
                                break;
                        case "ibm": //default port = ?
                                $this->conn[$database] = new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".$this->database."; HOSTNAME=".$this->host.";PORT=".$this->port.";PROTOCOL=TCcfP;", $this->user, $this->password);
                                break;
                        case "dblib": //default port = 10060
                                $this->conn[$database] = new PDO("dblib:host=".$this->host.":".$this->port.";dbname=".$this->database,$this->user,$this->password);
                                break;
                        case "odbc":
                                $this->conn[$database] = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=".$this->user);
                                break;
                        case "oracle":
                                $this->conn[$database] = new PDO("OCI:dbname=".$this->database.";charset=UTF-8", $this->user, $this->password);
                                break;
                        case "ifmx":
                                $this->conn[$database] = new PDO("informix:DSN=InformixDB", $this->user, $this->password);
                                break;
                        case "fbd":
                                $this->conn[$database] = new PDO("firebird:dbname=".$this->host.":".$this->database, $this->user, $this->password);
                                break;
                        case "sqlite2": //ej: "sqlite:/path/to/database.sdb"
                                $this->conn[$database] = new PDO("sqlite:".$this->host);
                                break;
                        case "sqlite3":
                                $this->conn[$database] = new PDO("sqlite::memory");
                                break;
                        case "pg":
                                $this->conn[$database] = (is_numeric($this->port)) ?
                                new PDO("pgsql:dbname=".$this->database.";port=".$this->port.";host=".$this->host, $this->user, $this->password)
                                : new PDO("pgsql:dbname=".$this->database.";host=".$this->host, $this->user, $this->password);
                                break;
                        default:
                                $this->conn[$database] = null;
                                break;
               endswitch;
                $this->_set_object($database,$this->conn[$database]);
                $this->conn[$database]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn[$database]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                return  $this->conn[$database];
        }catch(PDOException $e){
               echo  $this->err_msg = "Error: ". $e->getMessage();
                return false;
        }
    else:
            $this->err_msg = "Error: Error establishing a database connection (error in params or database not supported).";
            return false;
    endif;
    }

    public function _set_object($name, $value)
    {
        $this->conn[$name] = $value;
    }

    public function getInstance($key)
    {
        return (!is_null($this->conn[$key])) ? $this->conn[$key] : '';
    }

}