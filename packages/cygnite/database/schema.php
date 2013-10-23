<?php
namespace Cygnite\Database;

use Cygnite\Singleton;
use Cygnite\Database\Connections;
use Closure;

class Schema extends Singleton
{
    public static $database;

    private static $_pointer;

    public $tableName;

    public static $primaryKey;

    const ALTER_TABLE = 'ALTER TABLE ';

    public $schema =array();

    private static $_connection;

    private $_informationSchema = 'information_schema';

    private $_tableSchema = 'table_schema';

    private static $config;

    public static function getInstance($_pointer, Closure $setup)
    {
        static::$_pointer = $_pointer;
        if (class_exists(get_class(static::$_pointer))) {

            $reflectionClass = new \ReflectionClass(get_class(static::$_pointer));
            $reflectionProperty = $reflectionClass->getProperty('database');
            $reflectionProperty->setAccessible(true);
            $reflectionPropertyKey = $reflectionClass->getProperty('primaryKey');
            $reflectionPropertyKey->setAccessible(true);
            static::$database = $reflectionProperty->getValue(static::$_pointer);
            self::$primaryKey = $reflectionPropertyKey->getValue(static::$_pointer);
            static::$_connection = Connections::get(static::$database);
        }

        self::$config = Connections::getConfiguration();

        $setup(parent::instance());
    }

    public function create($columns, $engine = 'MyISAM', $charset = 'utf8')
    {
        $schema = $comma = $isNull = $tableKey = $type = "";

        $config = Connections::getConfiguration();
        $charset = $config->charset;

        $schema .= strtoupper(__FUNCTION__).' TABLE IF NOT EXISTS
        `'.static::$database.'`.`'.$this->tableName.'` (';
        $arrCount = count($columns);
        $i= 0;

        foreach ($columns as $key => $value) {

            $increment = (isset($value['increment'])) ? 'AUTO_INCREMENT' : '';
            if (isset($value['key'])) {

                $tableKey = strtoupper($value['key']).' ';

                if ($value['key'] == 'primary') {
                    $tableKey = 'PRIMARY KEY';
                }

            } else {
                $tableKey = '';
            }

            $isNull = (isset($value['null']) &&
                $value['null'] == true
            ) ? ' DEFAULT NULL' : ' NOT NULL';

            switch ($value['type']) {

                case 'int':
                    $length = ($value['length'] !=='') ? $value['length'] : 11;
                    $type = 'INT('.$length.')';
                    break;
                case 'char':
                    $length = ($value['length'] !=='') ? $value['length'] : 2;
                    $type = 'char('.$length.')';
                    break;
                case 'string':
                    $length = ($value['length'] !=='') ? $value['length'] : 200;
                    $type = 'VARCHAR('.$length.')';
                    break;
                case 'text':
                    $type = 'TEXT';
                    break;
                case 'longtext':
                    $type = 'longtext';
                    break;
                case 'float':
                    $length = ($value['length'] !=='') ? $value['length'] : '10,2';
                    $type = 'FLOAT('.$length.')';
                    break;
                case 'decimal':
                    $length = ($value['length'] !=='') ? $value['length'] : '10,2';
                    $type = 'DECIMAL('.$length.')';
                    break;
                case 'enum':
                    $length = implode('","', $value['length']);
                    $type = 'ENUM("'.$length.'")';
                    break;
                case 'date':
                    $type = 'date';
                    break;
                case 'datetime':
                    $length = $value['length'];
                    $type = 'datetime ' .$length;
                    break;
                case 'time':
                    $type = 'time';
                    break;
                case 'timestamp':
                    $length = $value['length'];
                    $type = 'timestamp ' .$length;
                    break;
            }

            $comma =  ($i < $arrCount-1) ? ',' : '';

            $schema .= '`'.$value['name']."` ".strtoupper($type)." "
                .$increment." ".$tableKey.$isNull;
            $schema .= $comma;

            $i++;
        }

        $schema.= ') ENGINE='.$engine.' DEFAULT  CHARSET='.$charset.';';

        $this->schema = $schema;

        return $this;
    }

    public function drop($table = '')
    {
        $tableName = '';

        $tableName = ($table !=='') ?
            $table : $this->tableName;

        $this->schema = 'DROP TABLE IF EXISTS `'.static::$database.'`.`'.$tableName.'`';

        return $this;
    }

    /**
     * Rename the database table.
     *
     * @param  array|string $tableNames
     * @return this pointer
     *
     */
    public function rename($tableNames = array())
    {
        $schema = '';

        $schema .= strtoupper(__FUNCTION__).' TABLE ';

        if (is_array($tableNames)) {

            $i=0;

            $arrCount = count($tableNames);

            foreach ($tableNames as $key => $value) {

                $schema .= '`'.$key.'` TO `'.$value.'`';

                $comma =  ($i < $arrCount-1) ? ',' : '';
                $schema .= $comma;
                $i++;
            }
        } else {
            $schema .= '`'.static::$database.'.`'.$this->tableName.'` TO `'.$tableNames.'`';
        }

        $this->schema = $schema;

        return $this;
    }

    public function hasTable($table = '')
    {
        $tableName = '';

        $tableName = ($table !== '') ?
            $table : $this->tableName;

        $this->schema = "SHOW TABLES LIKE '".$tableName."'";

        return $this;
    }

    public function alter()
    {

    }

    public function __call($name, $arguments)
    {
        $callMethod = null;

        if (strpos($name, 'Column') == true) {

            $callMethod = explode('Column', $name);

            if (trim($callMethod[0]) == 'drop') {
                $arguments[1] = trim($callMethod[0]);
                $arguments[2] = trim($callMethod[0]);
            }

            //var_dump($callMethod);

            //var_dump($arguments);
            if (trim($callMethod[0]) == 'add') {

                if (isset($arguments[0]) && is_array($arguments[0])) {
                    $arguments[1] = '';
                    $arguments[2] = trim($callMethod[0]);
                } else {
                    $arguments[2] = trim($callMethod[0]);
                }
            }

            //var_dump($arguments);

            if (!empty($arguments)) {
                if (method_exists($this, 'column')) {
                    return call_user_func_array(array($this, 'column'), $arguments);
                }
            }
        }

        throw new \BadMethodCallException("Invalid method $name called ");
    }

    /**
     *
     *
     *
     */
    public function column($columns, $defination = null, $type)
    {
        if (is_null($columns)) {
            throw new \BadMethodCallException("Column cannot be empty.");
        }

        if (is_array($columns)) {

            $column  = $columnKey = $columnValue= '';
            $i = 0;
            $arrCount = count($columns);

            foreach ($columns as $key => $value) {

                if (trim($type) == 'drop') {
                    $columnKey = '';
                    $columnValue = "`$value`";
                    $column .= strtoupper(trim($type)).' '.$columnKey.' '.$columnValue;
                }

                if (trim($type) == 'add') {
                    $columnKey = $key;
                    $columnValue = strtoupper($value);
                    $column .= strtoupper($type).' ('.$columnKey.' '.$columnValue.')';
                }

                $comma =  ($i < $arrCount-1) ? ',' : '';

                $column .= $comma;

                $i++;
            }

            $this->schema = self::ALTER_TABLE.'`'.static::$database.'`.`'.$this->tableName.'`
            '.$column.';';
        }

        if (is_string($columns)
            && $defination != null
        ) {
            /** @var $defination TYPE_NAME */
            $defination =(trim($type) == 'drop') ? '' : strtoupper($defination);

            $this->schema = self::ALTER_TABLE.'`'.static::$database.'`.`'.$this->tableName.'`
            '.strtoupper($type).' `'.$columns.'` '.$defination.';';
        }

        return $this;

    }

    public function modifyColumn()
    {

    }

    public function after($column)
    {

        $this->schema = str_replace(';', ' ', $this->schema).''.strtoupper(__FUNCTION__).' '.$column.';';

        return $this;

    }

    /**
     *
     */
    public function hasColumn($column)
    {
        $this->schema = "SELECT COUNT(COLUMN_NAME) FROM
                        ".$this->_informationSchema.".COLUMNS
                        WHERE TABLE_SCHEMA = '".static::$database."'
                        AND TABLE_NAME = '".$this->tableName."'
                        AND COLUMN_NAME = '".$column."' ";

        return $this;

    }
    // string for single column and array for multiple column
    public function addPrimaryKey($columns)
    {
        $schema = "SELECT EXISTS
                   (
                       SELECT * FROM ".$this->_informationSchema.".columns
                       WHERE ".$this->_tableSchema."= '".static::$database."' AND
                       table_name ='".$this->tableName."' AND
                       column_key = 'PRI'

                   ) AS has_primary_key;";

        $hasPrimaryKey = static::$_connection->prepare($schema)->execute();

        if ($hasPrimaryKey === true) {
            $query = '';
            $query = self::ALTER_TABLE."`".$this->tableName."` CHANGE
             `".self::$primaryKey."` `".self::$primaryKey."` INT( 11 ) NOT NULL";
            $primaryKey = static::$_connection->prepare($query)->execute();

            $schemaString = '';
            $schemaString = $this->commands($columns);

            $this->schema = static::ALTER_TABLE.' `'.static::$database.'`.`'.$this->tableName.'` DROP PRIMARY KEY,
                ADD CONSTRAINT PK_'.strtoupper($this->tableName).'_ID
                PRIMARY KEY ('.$schemaString.')';
        } else {
            $this->schema = static::ALTER_TABLE.' `'.static::$database.'`.`'.$this->tableName.'` ADD
            '.'PRIMARY KEY ('.$columns.')';
        }

        return $this;

    }

    public function dropPrimary()
    {
        echo self::$config->dbType;
        $this->schema = static::ALTER_TABLE.'
        `'.static::$database.'`.`'.$this->tableName.'` DROP PRIMARY KEY';

    }

    private function commands($params)
    {

        if (is_array($params)) {

            $param  = '';
            $i = 0;
            $arrCount = count($params);

            foreach ($params as $key => $value) {

                $param .=  $value;

                $comma =  ($i < $arrCount-1) ? ',' : '';

                $param .= $comma;

                $i++;
            }

        }

        if (is_string($params)) {
            $param = $params;
        }

        return $param;
    }


    public function unique($column, $keyConstraint = '')
    {
        $alter = '';
        $alter = static::ALTER_TABLE.' `'.$this->tableName.'` ADD ';

        if (is_array($column)) {
            // build query unique key for multiple columns
            $columns = $this->commands($column);

            $this->schema = $alter.' CONSTRAINT
            UC_'.strtoupper($this->tableName).'_'.strtoupper($keyConstraint).'_ID
            '.strtoupper(__FUNCTION__).' ('.$columns.')';

        }

        if (is_string($column)) {
            $this->schema = $alter.' '.strtoupper(__FUNCTION__).' ('.$column.');';
        }

        return $this;
    }

    public function dropUnique($keyConstraint = '')
    {
        // ALTER TABLE Persons DROP CONSTRAINT uc_PersonID
        // MYSQL QUERY
        $this->schema = static::ALTER_TABLE.' `'.$this->tableName.'`
            DROP INDEX
            UC_'.strtoupper($this->tableName).'_'.strtoupper($keyConstraint).'_ID';

        return $this;

    }

    public function index($columnName, $indexName = '')
    {
        $indexName = ($indexName !== '') ? $indexName : $columnName;
        $this->schema = 'CREATE INDEX '.strtoupper($indexName).'_INDEX
        ON `'.$this->tableName.'` ('.$columnName.')';

        return $this;
    }

    public function dropIndex($indexName)
    {
        $this->schema = static::ALTER_TABLE.' `'.$this->tableName.'`
            DROP INDEX '.strtoupper($indexName).'_INDEX';

        return $this;
    }

    //Foreign key References to table
    public function addForeignKey()
    {

    }

    public function referenceTo()
    {

    }

    public function dropForeignKey()
    {


    }

    public function onDelete()
    {

    }

    public function onSave()
    {

    }

    public function run()
    {
        //$connection = Connections::get(static::$database);
        //echo "<br>";
        //echo $this->schema;
        //echo "<br>";
        if (is_object(static::$_connection)) {

            if ($return = static::$_connection->prepare($this->schema)->execute()) {
                return $return;
            } else {
                return false;
            }
        }
    }
}