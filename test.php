<?php
//$this->users->username = 'Sanjay'; // Where users is the table name and username is the column name
//$this->users->username = 'Sanjay';
//$this->users->save(); where users is the table name ..

// For Delete Operation -

// $this->users->delete(array('id'=>$id));
abstract class Connections
{
    function connect($key)
    {
        echo "Connected with $key";
    }
}

class CF_Mysql_Driver extends Connections
{
    public function __construct($key)
    {
        $this->connect($key);
    }

}

abstract class Adapter
{
    static function getdriver($type,$db)
    {
            $class = 'CF_'.ucfirst($type).'_Driver';
            return new $class($db);
   }
}

class BaseModel //extends Activerecords
{
    private $driver,$db;
    private $activearr = array();

    function __construct($db)
    {
        $this->db = $db;
        $dbconfig = array(
                       'db' =>array(
                                    'hostname' => 'localhost',
                                    'username'  => 'root',
                                    'password'  => '',
                                    'dbname'    => 'cygnite',
                                    'dbprefix'  => '',
                                    'dbtype'    => 'mysql',
                                    'port'        => '',
                                    'pconnection' =>TRUE
                    )
                  ,'db2' => array(
                                    'hostname' => 'localhost',
                                    'username'  => 'root',
                                    'password'  => '',
                                    'dbname'    => 'hris',
                                    'dbprefix'  => '',
                                    'dbtype'    => 'mysql',
                                    'port'        => '',
                                    'pconnection' =>TRUE
                    )

            );

            array_keys($dbconfig);
            $search =array_search('db', $dbconfig);
            var_dump($search );
        $this->driver = Adapter::getdriver('mysql',$db);
    }

    function __set($name, $value)
   {
            $this->activearr[$name] = $value;
    }

    function __get($name)
    {
         return $this->activearr[$name];
    }

    function save()
    {
        var_dump($this->activearr);
    }


}

class Model extends BaseModel
{
    function __construct()
    {
        parent::__construct('test');
    }

    function test()
    {
        $this->username = 'Sanjay';

    }
    function testing()
    {
        $this->username = 'willians';
        $this->save('users');
        return 'saved';
    }
}
$t = new Model;
$t->test();
echo $t->testing();
exit;
class CF_DB_Mysql_Driver
{
    function set()
    {
        echo "Here now";
    }

}

class CF_DB_Oracle_Driver
{

}

class CF_DBAdapter
{
    private function __construct() { }

    public static function getdriver($class)
    {
        $class = 'CF_DB_'.ucfirst($class).'_Driver';
        if(class_exists($class))
             return new $class();
        else
            throw new Exception;
    }

}

class Connection
{
    protected function connect()
    {
        $this->set_connection();
    }

     private function set_connection()
    {

    }

}

class CF_ActiveRecords extends CF_DBAdapter
{
     public $activearr = array();

     function __construct($connkey)
    {

    }

      public function __set($key,$value)
    {
         $this->activearr[$key] = $value;
    }

    public function __get($key)
    {
        return $this->activearr[$key];
    }

    function save()
    {
        echo "Here I am now";
        $ob = CF_DBAdapter::getdriver('Mysql');
        $ob->set();
        var_dump($this->activearr);
    }
}



class CF_BaseModel //extends Active
{
    protected $db,$instance;

    protected function __construct($connkey)
    {
            $this->db = $this->get_instance($connkey);
    }

    private function get_instance($connkey)
    {
         if(is_null($this->instance))
                 $this->instance = new CF_ActiveRecords($connkey);

         return $this->instance;
    }

 }

class Buser extends CF_BaseModel
{
    public function __construct()
    {
        parent::__construct('cygnite');
    }

    public function testing()
    {
          $this->db->username = 'Sanjoy';
          $this->db->author = 'Sanjoy ';
          $this->db->save();
    }

    public function get()
    {
        $this->db->select()->where()->fetchall();
    }

}
$obj = new Buser();
$obj->testing();

?>
