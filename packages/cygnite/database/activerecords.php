<?php
namespace Cygnite\Database;

use Cygnite\Database\Connections;
use Cygnite\Database\Configurations;
use Cygnite\Database\Exceptions\DatabaseException;
use PDO;
use PDOException;

class ActiveRecords extends Connections
{
    public $pdo;

    public $closed;

    public $attributes = array();

    public $data = array();

    private $_statement;

    private $_selectColumns;

    private $_fromWhere;

    private $_columnWhere;

    private $_whereType;

    private $_limitValue;

    private $_offsetValue;

    private $_columnName;

    private $_orderType;

    private $_groupBy;

    protected $database;

    protected $tableName;

    protected $primaryKey;

    private $sqlQuery;

    private $debugQuery;

    private $distinct;

    protected function __construct()
    {
        $config = Configurations::instance();

        if (is_null($this->database)) {
            throw new \InvalidArgumentException(
                "Please specify database in your model.".get_called_class()
            );
        }

        if (is_null($this->tableName)) {
            throw new \InvalidArgumentException(
                "Please specify Table Name in your model.".get_called_class()
            );
        }

        foreach ($config->connections as $key => $value) {

            if (preg_match('/'.$this->database.'/', $value, $m)) {
                $this->pdo = $this->setConnection($value);
            }
        }
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        try {
            return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    public function __call($name, $arguments)
    {
        if ($name == 'save') {
            if (empty($arguments)) {
                if (method_exists($this, 'saveInTable')) {
                    return call_user_func_array(array($this, 'saveInTable'), array());
                }
            } else {
                if (method_exists($this, 'updateTable')) {
                    return call_user_func_array(array($this,'updateTable'), $arguments);
                }
            }
        }
        throw new \Exception("Invalid method $name called  ");
    }

    private function saveInTable($arguments = array())
    {
        $fields = $values = array();
        $query = $debugQuery = "";
        foreach (array_keys($this->attributes) as $key) {

             $fields[] = "`$key`";
             $values[] = "'" .$this->attributes[$key] . "'";
             $placeholder[] = substr(str_repeat('?,', count($key)), 0, -1);
        }

        $fields = implode(',', $fields);
        $values = implode(',', $values);
        $placeHolders = implode(',', $placeholder);

        $query = "INSERT INTO `".$this->database."`.`".$this->tableName."`
           ($fields) VALUES"." ($placeHolders)".";";

        $debugQuery = "INSERT INTO `".$this->database."`.`".$this->tableName."`
           ($fields) VALUES"." ($values)".";";
        //$this->setdDebugQuery($query);


        try {
            //$this->pdo->quote($string, $parameter_type=null); have to write a method to escape strings
            $statement = $this->pdo->prepare($query);
            $statement->execute(array_values($this->attributes));

            return true;

        } catch (PDOException  $exception) {
             throw new DatabaseException($exception); //echo  $exception->getMessage();
        }
    }

    private function updateTable($args)
    {
        $query  =$debugQuery= $x = "";
        $updateBy = $updateValue = null;

        if ((is_array($args) && !empty($args) )) {
            $x = array_keys($args);
            $updateBy = $x[0];
            $updateValue = $args[$x[0]];
        } else {
            $updateBy = $this->primaryKey;
            $updateValue = $args;
        }


        $query .="UPDATE `".$this->database."`.`".$this->tableName."` SET ";
        $debugQuery .="UPDATE `".$this->database."`.`".$this->tableName."` SET ";
        $arrCount = count($this->attributes);
        $i = 0;

        foreach ($this->attributes as $key => $value) {

            $query .= " `".$key."` "."="." '".$value."'"." ";
            $debugQuery .= " `".$key."` "."="." '".$value."'"." ";
            $query .=  ($i < $arrCount-1) ? ',' : '';
            $debugQuery .=  ($i < $arrCount-1) ? ',' : '';

            $i++;
        }

            $query .=" WHERE ".$updateBy." =  :column";
            $debugQuery .=" WHERE ".$updateBy." = ".$updateValue;
            //$this->debugLastQuery($debugQuery);
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':column', $updateValue);

            return $statement->execute();

        } catch ( \PDOException  $exception) {
               echo  $exception->getMessage();
        }
    }

    /**
     * Trash method
     *
     * Delete rows from the table and runs the query
     *
     * @access    public
     * @param array $where
     * @throws Exceptions\DatabaseException
     * @internal  param \Cygnite\Database\the $string table to retrieve the results from
     * @return object
     */
    public function trash($where)
    {
        $whr = array();
        $column = $value = null;

        if (is_array($where)) {
            $whr = array_keys($where);
            $column = $whr[0];
            $value = $where[$whr[0]];
        } else {
            $column = $this->primaryKey;
            $value = $where;
        }

        $sqlQuery = "DELETE FROM `".$this->tableName."` WHERE `".$column."` = :where";
        $debugQuery = "DELETE FROM `".$this->tableName."` WHERE `".$column."` = ".$value;

        /** @var $exception TYPE_NAME */
        try {
            $statement = $this->pdo->prepare($sqlQuery);
            //Cygnite::loader()->request('SQLUtilities')->setdbstmt($statement,$this->pdo);
            $statement->bindValue(':where', $value);

            return $statement->execute();

        } catch (\PDOException  $exception) {
            throw new DatabaseException($exception);
        }

    }

    /**
    * Find Function to selecting Table columns
    *
    * Generates the SELECT portion of the query
    *
    * @access	public
    * @param	string
    * @return	object
    */
    public function select($type)
    {
        //create where condition with and if value is passed as array
        if (is_string($type) && !is_null($type)) {
            if ($type === 'all' || $type == '*') {
                $this->_selectColumns = '*';
            } else {
                $this->_selectColumns = (string) $type; // Need to split the column name and add quotes
            }
        }

        return $this;
    }

    /* Where
    *
    * Generates the WHERE portion of the query. Separates
    * multiple calls with AND
    * You can also use this method for WHERE IN(),
    * OR WHERE etc.
    * Example:
    * <code>
    * $this->where('field_name','value','=');
    *
    * $conditions = array(
    *                     'field_name1 LIKE' => '%Sanjoy%',
    * );                  'field_name2 LIKE' => 'Dey%',
    *
    * $this->where($conditions);
    *
    * $conditions2 = array(
    *                     'field_name1 LIKE' => '%Sanjoy%',
    * );                  'field_name2 =' => 'Dey',
    *
    * $this->where($conditions2,'OR');
    *
    *
    * $conditions3 = array(
    *                     'field_name1 IN' => '#"Automatic","Automated","Autoclaves"'
    * );
    *
    * $this->where($conditions2,'OR');
    *
    * $conditions4 = array(
    *                    'created_at BETWEEN' => '2012-12-27',
                          date('Y-m-d'),
    * );
    *
    * $this->where($conditions4);
    * </code>
    *
    * @access	public
    * @param	column name
    * @param	value
    * @return	object
    */
    public function where($columnName, $where = "", $type = null)
    {
        $resultArray = array();
        // Check whether value passed as array or not
        if (is_array($columnName)) {

            $arrayCount = count($columnName);
            $resultArray = $this->extractConditions($columnName);
            $arrayCount = count($resultArray);
            $i = 0;
            $whereValue = $whereCondition= "";

            foreach ($resultArray as $row) {

                $whereField = "`".$row['0']."`";

                if ($row['0'] === null) {
                    $whereField = '';
                }

                $whereCondition  = (is_string($row['1'])) ? strtoupper($row['1']) : $row['1'] ;

                if (preg_match('/#/', $row['2'])) {
                    $whereValue = str_replace('#', '(', $row['2']).')';
                } else {
                    $whereValue  =  (is_string($row['2'])) ? " '".$row['2']."'" :  $row['2'] ;
                }

                $whereType = '';
                $this->_fromWhere .= $whereField." ".$whereCondition.$whereValue;
                $whereType = ($where == '') ? ' AND' : ' '.$where.' ';
                $this->_fromWhere .= ($i < $arrayCount-1) ? $whereType  :  '';
                $this->_whereType = '';

                $i++;

            }

            return $this;
        }

        if (is_string($columnName)) {
            $columnName = "`".$columnName."`";
        }

        $this->_whereType = '=';
        $this->_columnWhere = $columnName;
        $this->_fromWhere = " '".$where."' ";

        if (!is_null($type)) {
            $this->_whereType  = $type;
        }

        return $this;
    }

    private function extractConditions($arr)
    {
        //$pattern  = '/([A-Za-z_]+[A-Za-z_0-9]?)[ ]?(!=|=|<=|<|>=|>|like|clike|slike|not
        //              |is|in|between|and|or|IN|NOT|BETWEEN|LIKE|AND|OR)/';
        $pattern  = '/([\w]+)?[\s]?([\!\<\>]?\=|[\<\>]|[cs]{0,1}like|not
                    |i[sn]|between|and|or)?/i';

        $result = array();
        foreach ($arr as $key => $value) {

            preg_match($pattern, $key, $matches);
            $matches[1] = !empty($matches[1]) ? $matches[1] : null;
            $matches[2] = !empty($matches[2]) ? $matches[2] : null;

            $result []= array($matches[1], $matches[2], $value);

        }

        return $result;
    }

    public function distinct($column)
    {
        $this->distinct = "DISTINCT($column)";

        return $this;
    }

    /*
    * limit function to limit the database query
    * @access   public
    * @param    int
    * @return   object
    */
    public function limit($limit, $offset = "")
    {
        if ($limit == ' ' || is_null($limit)) {
            throw new \Exception('Empty parameter given to limit clause ');
        }

        if (empty($offset) && !empty($limit)) {
            $this->_limitValue = 0;
            $this->_offsetValue = intval($limit);
        } else {
            $this->_limitValue = intval($limit);
            $this->_offsetValue = intval($offset);
        }

        return $this;
    }

    public function groupBy($column)
    {
        if (is_null($column)) {
            throw new \InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
        }

        $groupBy = "";
        switch ($column) {
            case is_array($column):
                $i = 0;
                  $count = count($column);
                while ($i < $count) { //Create group by in condition with and if value is passed as array
                        $groupBy .= '`'.$column[$i].'`';
                        $groupBy .= ($i < $count-1) ? ',' : '';
                        $i ++;
                }
                $this->_groupBy = 'GROUP BY '.$groupBy;

                return $this;
            break;
            default:
                  $this->_groupBy = 'GROUP BY `'.$column.'` ';//exit;

                return $this;
            break;
        }
    }

    public function quote()
    {
        // escape strings
    }

    /*
    * orderBy function to make order for selected query
    * @access   public
    * @param    string
    * @param    string
    * @return   object
    */
    public function orderBy($filedName, $orderType = "ASC")
    {
        if (empty($filedName)) {
            throw new \Exception('Empty parameter given to order by clause');
        }

        if ($this->_columnName === null && $this->_orderType === null) {
            $this->_columnName = $filedName;
        }
        $this->_orderType = $orderType;

        return $this;
    }

    public function toJson()
    {
        $this->serialize = 'json';

        return $this;
    }

    public function toXML()
    {
        $this->serialize = 'xml';

        return $this;
    }

    /**
     * Build and Find all the matching records from database.
     * By default its returns class with properties values
     * You can simply pass fetchMode into findAll to get various
     * format output.
     *
     * @access   public
     * @param  string $fetchMode
     * @throws \Exception
     * @internal param string $fetchMode
     * @return array or object
     */
    public function findAll($fetchMode = "")
    {
        $data = array();
        $limit = "";

        if (is_null($this->_selectColumns)) {
            $this->_selectColumns = '*';
        }

        $groupBy =(isset($this->_groupBy) && !is_null($this->_groupBy)) ? $this->_groupBy : '';

        $limit =  (isset($this->_limitValue)  && isset($this->_offsetValue)) ?
               " LIMIT ".$this->_limitValue.",".$this->_offsetValue." "  :  '';

        $orderBy= (isset($this->_columnName)  && isset($this->_orderType)) ?
               " ORDER BY `".$this->_columnName."`  ".$this->_orderType  :  '';

          $this->buildQuery($groupBy, $orderBy, $limit);

        try {
             $statement = $this->pdo->prepare($this->sqlQuery);
             $this->setDbStatement($this->database, $statement);
             $statement->bindValue(':where', $this->_fromWhere);
             $statement->execute();
             $data = $this->fetchAs($statement, $fetchMode);

            if ($statement->rowCount() > 0) {
                return $data;
            }
        } catch (PDOException $ex) {
            throw new \Exception("Database exceptions: Invalid query x".$ex->getMessage());
        }
    }

    private function fetchAs($statement, $fetchMode)
    {
        $data = array();

        switch ($fetchMode) {
            case 'FETCH_GROUP':
                $data = $statement->fetchAll(PDO::FETCH_GROUP| PDO::FETCH_ASSOC);
                break;
            case 'FETCH_BOTH':
                $data = $statement->fetchAll(PDO::FETCH_BOTH);
                break;
            case 'JSON':
                $data = json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
                break;
            case 'FETCH_OBJ':
                $data = $statement->fetchAll(PDO::FETCH_OBJ);
                break;
            case 'FETCH_ASSOC':
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'FETCH_COLUMN':
                $data = $statement->fetchAll(PDO::FETCH_COLUMN);
                break;
            default:
                $data = $statement->fetchAll(PDO::FETCH_CLASS, '\\'.__NAMESPACE__.'\\Datasource');
        }

        return $data;

    }

    public function rowCount()
    {
        $statement = $this->getDbStatement($this->database);
        return $statement->rowCount();
    }

    private function buildQuery($groupBy, $orderBy, $limit)
    {
        $searchKey = strpos($this->_fromWhere, 'AND');

        if ($searchKey === false) {
            ($this->_columnWhere)
                ?
                   $where = '  WHERE  '.$this->_columnWhere.' =  :where '
                :  $where = ' ';

            $where = (is_null($this->_columnWhere) && is_null($this->_fromWhere))
                ? ''
                : ' WHERE  '.$this->_columnWhere." $this->_whereType ".$this->_fromWhere."";

            $this->debugQuery = "SELECT ".$this->_selectColumns." FROM `".$this->tableName.'`'.$where.
                                ' '.$groupBy.' '.$orderBy.$limit;
            $this->sqlQuery = "SELECT ".$this->_selectColumns." FROM `".$this->tableName.'` '.$where.
                                ' '.$groupBy.' '.$orderBy.$limit;
        } else {

            ($this->_fromWhere !="")
                ?
                $where = " WHERE ".$this->_fromWhere
                :
                $where = "";
             $this->debugQuery = "SELECT ".$this->_selectColumns." FROM `".$this->tableName.'` '.$where.' '.
                $groupBy.' '.$orderBy.$limit;
            $this->sqlQuery = "SELECT ".$this->_selectColumns." FROM `".$this->tableName.'` '.$where.' '.
                $groupBy.' '.$orderBy.$limit;
        }
    }

    ########

    public function query($sql)
    {
        $this->_statement = $this->pdo->query($sql);

        return $this;
    }

    public function find()
    {
        return $this->_statement->fetch();
    }

    public function getAll($fetchMode = PDO::FETCH_OBJECT)
    {
        $data = array();
        ob_start();
        $data  = $this->_statement->fetchAll($fetchMode);

        ob_end_clean();
        ob_end_flush();

        return $data;
    }

    /**
     * @param string     $req       : the query on which link the values
     * @param array      $array     : associative array containing the values ??to bind
     * @param array|bool $typeArray : associative array with the desired value for its corresponding key in $array
     */
    public function bindArrayValue($req, $array, $typeArray = false)
    {
        if (is_object($req) && ($req instanceof PDOStatement)) {
            foreach ($array as $key => $value) {
                if ($typeArray) {
                    $req->bindValue(":$key", $value, $typeArray[$key]);
                } else {
                    if (is_int($value)) {
                        $param = PDO::PARAM_INT;
                    } elseif (is_bool($value)) {
                        $param = PDO::PARAM_BOOL;
                    } elseif (is_null($value)) {
                        $param = PDO::PARAM_NULL;
                    } elseif (is_string($value)) {
                        $param = PDO::PARAM_STR;
                    } else {
                        $param = false;
                    }

                    if ($param) {
                        $req->bindValue(":$key", $value, $param);
                    }

                }
            }
        }
    }

    public function setDebug($sql)
    {
        $this->data[] = $sql;
    }

    protected function debugLastQuery($sql)
    {
        echo $sql;
    }

    public function getConnection($connection)
    {
        return is_resource($this->pdo) ? $this->pdo : null;
    }



    /*
    * Flush results after data retrieving process
    * It will unset all existing properties and close reader in order to make new selection process
    *
    */
    public function flush()
    {
        if ($this->isClosed() == false):
            $this->close();
            $this->closed = false;
            unset($this->_selectColumns);
            unset($this->_fromWhere);
            unset($this->_columnWhere);
            unset($this->_columnWhere_in);
            unset($this->_limitValue);
            unset($this->_columnName);
            unset($this->_offsetValue);
            unset($this->_orderType);
        endif;
    }

    private function setDbStatement($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getDbStatement($key)
    {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    /**
    * Closes the reader.
    * This frees up the resources allocated for executing this SQL statement.
    * Read attempts after this method call are unpredictable.
    */
    public function close()
    {
        $statement = null;
        $statement = $this->getDbStatement($this->database);

        $statement->closeCursor();
        $this->closed = true;
    }

    /**
    * whether the reader is closed or not.
    * @return boolean whether the reader is closed or not.
    */
    private function isClosed()
    {
        return $this->closed;
    }

    public function getModelProperties()
    {
        echo $this->tableName;
        echo $this->database;
        echo $this->primaryKey;
        var_dump($this->columns);

        /*$object = new ReflectionClass(get_class($this));
        $ob =  get_class($this);
        echo $object->getProperty('tableName')->getValue(new $ob);
        echo "<pre>";
        var_dump($object);
        echo "</pre>"; */

    }

    public function explainQuery()
    {
        $sql = $explain = "";
        $sql = 'EXPLAIN EXTENDED '.$this->sqlQuery;
        $explain = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $html = "";
        $html  .= "<html> <head><title>Explain Query</title>
                           <style type='text/css'>
                             #contetainer { font-family:Lucida Grande,Verdana,Sans-serif; font-size:12px;padding: 20px 20px 12px 20px;margin:40px; background:#fff; border:1px solid #D3640F; }
                           h2 { color: #990000;  font-size: 15px;font-weight: normal;margin: 5px 5px 5px 13px;}
                           p {   margin:6px; padding: 9px; }
                           </style>
                           </head><body>
       <div id='contetainer'>
            <table >
            <th>ID</th>
            <th>Query Type</th>
            <th>Table</th>
            <th>Type</th>
            <th>Possible Keys</th>
            <th>Key</th>
            <th>Key Length</th>
            <th>Ref</th>
            <th>Rows</th>
            <th>Filtered</th>
            <th>Extra</th>
            <tr>
            <td> ".$explain[0]['id']."</td>
            <td> ".$explain[0]['select_type']."</td>
            <td> ".$explain[0]['table']."</td>
            <td> ".$explain[0]['type']."</td>
            <td> ".$explain[0]['possible_keys']."</td>
            <td> ".$explain[0]['key']."</td>
            <td> ".$explain[0]['key_len']."</td>
            <td> ".$explain[0]['ref']."</td>
            <td> ".$explain[0]['rows']."</td>
            <td> ".$explain[0]['filtered']."</td>
            <td> ".$explain[0]['Extra']."</td></tr></table></div></body></html>";
        echo $html;//exit;
        unset($explain);
    }

    public function __destruct()
    {
        unset($this->attributes);
        unset($this->data);
    }
}