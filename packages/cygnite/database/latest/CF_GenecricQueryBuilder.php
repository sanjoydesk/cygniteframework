<?php 
class CF_GenericQueryBuilder extends CF_DBConnector
{   
    private $pdo;
    private  $connection;
    
    private $selectfields = NULL,$from_where =NULL,$field_where = NULL,
            $field_where_in,$from_where_in,$limit_value,
            $offset_value =NULL,$field_name=NULL,$order_type;
    /** Variable $sql, query string to execute. */
    private $sqlqry = '',$debugqry = NULL;
    private $where_type = NULL;

    private $_dbstatement = NULL;
    /** Variable $err_msg, always empty if not have errors. */
    private $_err = "";
    private $_closed = FALSE;
    
    
    
    public function __construct($connkey)
    {    
        $this->connect();    
        $this->connection = $connkey;
        $this->pdo[$this->connection] = $this->getInstance($this->connection);     
    }   
    
    
    private $dbstatement,$sqlqry;
    
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
         echo $this->sqlqry = "INSERT INTO `$tblName` ($fields) VALUES"." ($values)".";";
            try{
                 $this->dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                 $this->dbstatement->execute();
            } catch(CF_DBException  $exception){
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
                     $debugqry .=" WHERE ".$this->field_where." = ".$this->from_where;

                   echo $this->sqlqry = $qry; 
                 //  exit;
                    $this->debugqry = $debugqry; //exit;
            try{
                $this->dbstatement = $this->pdo[$this->connection]->prepare($this->sqlqry);
                $this->dbstatement->bindValue(':column',$updatekey[$x[0]]);
                return $this->dbstatement->execute();                
            } catch(PDOException  $exception){
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
                            $where = $whrvalue = $whrcondition= "";
                                foreach($result_array as $row):
                                      (is_string($row['2'])) ? $whrvalue  = " '".$row['2']."'" : $whrvalue = $row['2'] ;
                                      (is_string($row['1'])) ? $whrcondition  = strtoupper($row['1']) : $whrcondition = $row['1'] ;
                                     $this->from_where .= "`".$row['0']."`"." ".$whrcondition.$whrvalue;
                                    $this->from_where .=       ($i < $arrCount-1) ? ' AND ' :  '';
                                    $i++;
                                endforeach;
                           return $this;
               endif;

             if(is_string($filedName))
                 $filedName = "`".$filedName."`";

            $this->field_where = $filedName;
            $this->from_where = $where;
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
   public function where_in($filedName,$where="")
   {
        // Check whether value passed as array or not
       if(is_array($filedName)):
           /** @var $qry TYPE_NAME */
           $qry = "";
           $arrCount = count($filedName);
           $i = 0;
                  //Create where in condition with and if value is passed as array
                   foreach($filedName as $key=>$value) :
                       $this->from_where_in .= " `".$key."` "."="." '".$value."'"." ";
                       $this->from_where_in .=  ($i < $arrCount-1) ? ' AND ' : '';
                       $i++;
                 endforeach;
       return $this;
       else :
                       $this->field_where_in = $filedName;
                       $this->from_where_in = $where;
               return $this;
       endif;
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
         $limit = "  ";
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
               //  DB_SQLUtilities::setdbstmt($this->_dbstatement,$this->pdo[$this->connection]);
                 $this->_dbstatement->bindValue(':where',$this->from_where);
              //   Profiler::start();
                 $this->_dbstatement->execute();


         } catch(PDOException  $exception){
                   $this->_err = $exception;
                   //var_dump($this->_err->getTrace());
                  GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),$this->debugqry);
         }
        //$_dbstatement->debugDumpParams();

        switch($fetchmode):
                    case 'FETCH_ASSOC':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_ASSOC);
                                     break;
                    case 'FETCH_GROUP':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
                                     break;
                    case 'FETCH_BOTH':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_BOTH);
                                     break;
                     case 'FETCH_CLASS':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_CLASS ,'CFDbDataReader');
                                     break;
                    case 'FETCH_OBJECT':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_OBJ);
                                     break;
                     case 'FETCH_COLUMN':
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_COLUMN);
                                     break;
                    default :
                                     $datas = $this->_dbstatement->fetchAll(PDO::FETCH_ASSOC);
        endswitch;
     //echo round(memory_get_usage() / (1024*1024),3) .' MB<br />';

       //Profiler::end();
         if($this->_dbstatement->rowCount() > 0):
             return $datas;
         else:
             return $this->_dbstatement->rowCount();
         endif;
    }
    public function num_row_count()
    {
         return DB_SQLUtilities::num_row_count();
    }

    private function _processquery($tblname,$groupby,$orderby,$limit)
    {
         $searchedkey = strpos($this->from_where, "AND");

            if($searchedkey === FALSE):
                         ($this->field_where) ?  $where = '  WHERE  '.$this->field_where.' =  :where '    :  $where = ' ';
                         $where = (is_null($this->field_where) && is_null($this->from_where)) ? '' : ' WHERE  '.$this->field_where." = '".$this->from_where."'";

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
           return $this->pdo[$this->connection]->query($sql);
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
   /* public function select()
    { 
      $data = $this->pdo[$this->connection]->query("SELECT `tips_link`,`tips_description` FROM vod_tips")->fetchAll();
      return $data;
    }  */
    
    public function selectall() 
    { 
      $data = $this->pdo[$this->connection]->query("SELECT * FROM categories")->fetchAll();
      return $data;  
    }
    
    public function debug_query()
    {
         return CF_SQLUtilities::debugqry($this->debugqry,$this->_dbstatement);
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
    public function is_closed()
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
class CFDbDataReader { }