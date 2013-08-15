<?php
namespace Cygnite\Database;

use Cygnite\Cygnite;

class CF_ActiveRecords extends DBConnector //implements CF_IActiveRecords
{
    private $pdo;
    private  $connection;

    private $selectfields = NULL,
                  $from_where =1,
                  $field_where = '=1',
                 $field_where_in,
                $from_where_in,
                $limit_value,
                $offset_value =NULL,
                $field_name=NULL,
                $order_type;
    /** Variable $sql, query string to execute. */
    private $sqlqry = '',$debugqry = NULL;
    private $where_type = NULL;

    private $_dbstatement = NULL;
    /** Variable $err_msg, always empty if not have errors. */
    private $_err = "";
    private $_closed = FALSE;

    private $dbstatement;


    public function __construct($connkey)
    {
        $this->connect();
        $this->connection = $connkey;
        $this->pdo[$this->connection] = $this->getInstance($this->connection);
    }

    public function insert($tblName = "",$data = array(),$exclude = array())
    {
          $fields = $values = array();
          if( !is_array($exclude) ) $exclude = array($exclude);
              foreach(array_keys($data) as $key ) :
                  if( !in_array($key, $exclude) ) :
                          $fields[] = "`$key`";
                          $values[] = "'" .$data[$key] . "'";
                  endif;
              endforeach;

          $fields = implode(",", $fields);
          $values = implode(",", $values);
          $this->sqlqry = "INSERT INTO `$tblName` ($fields) VALUES"." ($values)".";";
            try{
                 $this->dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                 $this->dbstatement->execute();
            } catch(CFDBException  $exception){
                 echo  $exception->getMessage();
            }
          return TRUE;
    }

    public function update($tbl,$data,$updatekey)
    {
            $qry = "";
            $qry .="UPDATE ".$tbl." SET ";
            $debugqry .="UPDATE ".$tbl." SET ";
            $arrCount = count($data);
            $i = 0;
                    foreach($data as $key=>$value) :
                        $qry .= " `".$key."` "."="." '".$value."'"." ";
                        $debugqry .= " `".$key."` "."="." '".$value."'"." ";
                        $qry .=  ($i < $arrCount-1) ? ',' : '';
                        $debugqry .=  ($i < $arrCount-1) ? ',' : '';
                        $i++;
                    endforeach;
                    $x = array_keys($updatekey);

                     $qry .=" WHERE ".$x[0]." =  :column";
                     $debugqry .=" WHERE ".$x[0]." = ".$updatekey[$x[0]];

                    $this->sqlqry = $qry;
                    $this->debugqry = $debugqry; //exit;
            try{
                $this->dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                $this->dbstatement->bindValue(':column',$updatekey[$x[0]]);
                return $this->dbstatement->execute();
            } catch( \PDOException  $exception){
                   echo  $exception->getMessage();
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
    public function select($selecttype)
    {
                 // create where condition with and if value is passed as array
                 $callee = debug_backtrace();
                if(is_string($selecttype) && !empty($selecttype)):
                            if($selecttype === "all"):
                                    $this->selectfields = "*";
                            else:
                                    ($selecttype === "*") ?
                                            trigger_error('You are not allowed to use * for selecting all columns. Please use all instead of *', E_USER_ERROR)
                                           //GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', 'You are not allowed to use * for selecting columns. Please use "all" keyword instead of *.', __FILE__,$callee[0]['line'] ,TRUE)
                                    :  $this->selectfields = $selecttype; // Need to split the column name and add quotes
                           endif;
                endif;
        return $this;
    }

    /* Where
    *
    * Generates the WHERE portion of the query. Separates
    * multiple calls with AND
    *
    * @access	public
    * @param	column name
    * @param	value
    * @return	object
    */
     public function where($filedName,$where="",$type=NULL)
    {
                // Check whether value passed as array or not
        if(is_array($filedName)):
             $arrCount = count($filedName);
                $result_array = $this->extract_conditions($filedName);
                $arrCount = count($result_array);
                $i = 0;
                $whrvalue = $whrcondition= "";
                    foreach($result_array as $row):
                        (is_string($row['2'])) ? $whrvalue  = " '".$row['2']."'" : $whrvalue = $row['2'] ;
                        (is_string($row['1'])) ? $whrcondition  = strtoupper($row['1']) : $whrcondition = $row['1'] ;
                        $this->from_where .= "`".$row['0']."`"." ".$whrcondition.$whrvalue;
                        $wheretype = ($where == '') ? 'AND' : ' '.$where.' ';
                        $this->from_where .= ($i < $arrCount-1) ? $wheretype  :  '';
                        $this->where_type = '';
                        $i++;
                    endforeach;
               return $this;
        endif;

        if(is_string($filedName))
            $filedName = "`".$filedName."`";
       $this->where_type = '=';
       $this->field_where = $filedName;
       $this->from_where = "'".$where."'";
       if(!is_null($type))
           $this->where_type  = $type;

      return $this;
    }

    private function extract_conditions($arr)
    {
        $pattern  = '/([A-Za-z_]+[A-Za-z_0-9]?)[ ]?(!=|=|<=|<|>=|>|like|clike|slike|not|is|in|between|and|or|LIKE|AND|OR)/';
        $result = array();
        foreach($arr as $key => $value) :
                preg_match($pattern, $key, $matches);
                $result []= array($matches[1], $matches[2], $value);
        endforeach;
            return $result;
    }

   /***************************************************Need to work on where_in function ***************************************************** */

    /* Where In
   *
   * Generates the WHERE in portion of the query. Separates
   * multiple calls with AND
   *
   * @access	public
   * @param	column name
   * @param	value
   * @return	object
   */
   public function where_in($fileds)
   {
            // Check whether value passed as array
           if(is_array($fileds)):
               /** @var $qry TYPE_NAME */
                $fields = implode(",", $fields);
                      //Create where in condition with and if value is passed as array
                     $this->form_where = " IN(".$fields.")";
           return $this;
           endif;
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
   public function limit($limit,$offset="")
   {
               if($limit === " " && $limit == NULL)
                        trigger_error('Empty parameter given to limit clause ',E_USER_ERROR);

               if(empty($offset) && !empty($limit)):
                        $this->limit_value = 0;
                        $this->offset_value = intval($limit);
              else:
                         $this->limit_value = intval($limit);
                         $this->offset_value = intval($offset);
               endif;
       return $this;
   }

   public function group_by($column)
    {
         if(is_null($column))
               throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);

            switch($column):
                case is_array($column):
                            $i = 0;
                            $count = count($column);
                                while($i < $count): //Create group by in condition with and if value is passed as array
                                    $groupby .= '`'.$column[$i].'`';
                                    $groupby .= ($i < $count-1) ? ',' : '';
                                    $i ++;
                                endwhile;
                                         $this->group_by = 'GROUP BY '.$groupby;
                            return $this;
                    break;
                default :
                                         $this->group_by = 'GROUP BY `'.$column.'` ';
                           return $this;
                    break;
            endswitch;
    }

    /*
    * orderby function to make order for selected query
    * @access   public
    * @param    string
    * @param    string
    * @return   object
    */
    public function order_by($filed_name,$order_type="ASC")
    {
         if(empty($filed_name))
             trigger_error('Empty parameter given to order by clause',E_USER_ERROR);
         if($this->field_name === NULL && $this->order_type === NULL)
                 $this->field_name = $filed_name;
                  $this->order_type = $order_type;
         return $this;
   }


     public function fetch_all($tblname,$fetchmode="")
    {
        $datas = array();
         $limit = "";
         if(is_null($tblname))
                throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);

         if($this->selectfields === NULL)
                 $this->selectfields= "*";

         $groupby = (isset($this->group_by) && !is_null($this->group_by)) ? $this->group_by : '';
         $limit =   (isset($this->limit_value)  && isset($this->offset_value)) ? " LIMIT ".$this->limit_value.",".$this->offset_value." "  :  '';
         $orderby =   (isset($this->field_name)  && isset($this->order_type)) ? " ORDER BY ".$this->field_name."  ".$this->order_type  :  '';

          $this->_processquery($tblname,$groupby,$orderby,$limit);

         try{

                 $this->_dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                 Cygnite::loader()->sqlutilities->setdbstmt($this->_dbstatement,$this->pdo[$this->connection]);
                 $this->_dbstatement->bindValue(':where',$this->from_where);
              //   Profiler::start('select');
                 $this->_dbstatement->execute();


         } catch( \PDOException  $exception){
                   $this->_err = $exception;
                   GHelper::trace();
                  GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),$this->debugqry);
         }
        //$_dbstatement->debugDumpParams();

        switch($fetchmode):
                    case 'FETCH_ASSOC':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_ASSOC);
                                     break;
                    case 'FETCH_GROUP':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
                                     break;
                    case 'FETCH_BOTH':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_BOTH);
                                     break;
                     case 'FETCH_CLASS':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_CLASS ,'CFDataReader');
                                     break;
                    case 'FETCH_OBJECT':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_OBJ);
                                     break;
                     case 'FETCH_COLUMN':
                                     $datas = $this->_dbstatement->fetchAll(\PDO::FETCH_COLUMN);
                                     break;
                    default :
                                     $datas = $this->_dbstatement->fetchAll( \PDO::FETCH_ASSOC);
        endswitch;
     //echo round(memory_get_usage() / (1024*1024),3) .' MB<br />';
        //echo $this->_dbstatement->rowCount();
       //Profiler::end('select');
         if($this->_dbstatement->rowCount() > 0):
             return $datas;
         else:
             return $this->_dbstatement->rowCount();
         endif;
    }

    public function num_row_count()
    {
         return Cygnite::loader()->request('SQLUtilities')->num_row_count();
    }

    private function _processquery($tblname,$groupby,$orderby,$limit)
    {
          $searchedkey = strpos($this->from_where, "AND");
            if($searchedkey === FALSE):
                ($this->field_where) ?  $where = '  WHERE  '.$this->field_where.' =  :where '    :  $where = ' ';
                $where = (is_null($this->field_where) && is_null($this->from_where)) ? '' : ' WHERE  '.$this->field_where." $this->where_type ".$this->from_where."";

                $this->debugqry = "SELECT ".$this->selectfields." FROM `".$tblname.'`'.$where.' '.$groupby.' '.$orderby.$limit;
                 $this->sqlqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.' '.$groupby.' '.$orderby.$limit;
            else:
                ($this->from_where !="") ? $where = " WHERE ".$this->from_where :  $where = "";
                 $this->debugqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.' '.$groupby.' '.$orderby.$limit;
                 $this->sqlqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.' '.$groupby.' '.$orderby.$limit;
            endif;
    }


   /*
    *
    * @todo - have to work on this section in order to make it work as like fetch_all
    */
   public function fetch_row($sql)
   {
           $result = $this->pdo[$this->connection]->query($sql);
           return $result->fetch();
   }

   /*
    * @access public
    * @param string
    * @return query object
    */
   public function prepare_query($sql)
   {
       $this->sqlqry = $sql;
       return $this->pdo[$this->connection]->query($sql);
   }

   public function explain_query()
   {
       $sql = $explain = "";
       $sql = 'EXPLAIN EXTENDED '.$this->sqlqry;
       $explain = $this->pdo[$this->connection]->query($sql)->fetchAll( \PDO::FETCH_ASSOC);
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

   /*
    * Flush results after data retriving process
    * It will unset all existing properties and close reader in order to make new selection process
    *
    *
    */
   public function flushresult()
   {
        if($this->is_closed() == FALSE):
             $this->close();
             $this->_closed=FALSE;
             unset($this->selectfields);unset($this->from_where);unset($this->field_where);
             unset($this->field_where_in);unset($this->limit_value);unset($this->field_name);
             unset($this->offset_value);unset($this->order_type);
        endif;
   }


   /**
   * Delete function
   *
   * Delete rows from the table and runs the query
   *
   * @access	public
   * @param	string	the table to retrieve the results from
   * @return	object
   */
    public function remove($tblname,$whrarr = array())
   {
          $whr = array();
          $whr = array_keys($whrarr);

           $this->sqlqry = "DELETE FROM `".$tblname."` WHERE `".$whr[0]."` = :where";
           $this->debugqry = "DELETE FROM `".$tblname."` WHERE `".$whr[0]."` = ".$whrarr[$whr[0]];

           try{
                $this->dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                Cygnite::loader()->request('SQLUtilities')->setdbstmt($this->dbstatement,$this->pdo[$this->connection]);
                $this->dbstatement->bindValue(':where',$whrarr[$whr[0]]);
                return $this->dbstatement->execute();
           } catch(CF_DBException  $exception){
                $this->_err = $exception;
                 GHelper::trace();
                GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
           }

   }

    public function debug_query()
    {
         return Cygnite::loader()->request('SQLUtilities')->debugqry($this->debugqry,$this->_dbstatement);
    }

    public function dblogerror() { }

    /**
    * Closes the reader.
    * This frees up the resources allocated for executing this SQL statement.
    * Read attemps after this method call are unpredictable.
    */
    private function close()
    {
            $this->_dbstatement->closeCursor();
            $this->_closed=TRUE;
    }

    /**
     * whether the reader is closed or not.
     * @return boolean whether the reader is closed or not.
     */
    private function is_closed()
    {
            return $this->_closed;
    }

function __destruct()
{
        unset($this->selectfields);unset($this->from_where); unset($this->field_where);
        unset($this->field_where_in);unset($this->limit_value);unset($this->field_name);
        unset($this->offset_value);unset($this->order_type);unset($this->sqlqry);
        unset($this->last_id);unset($this->_dbstatement);
}

}
class CFDataReader { }