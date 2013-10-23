<?php
namespace Cygnite\Database;

use PDO;
use Cygnite\Database\Configurations;
use Cygnite\Database\Connections;

class table
{
    public $tableName;

    public $primaryKey;

    public $connection;

    private static $instance;

    private static $object =array();

}
