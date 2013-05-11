<?php
       /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @todo                              :   Have to implement for group by, where in, distinct, like, having, aggregate functions, truncate , Join functions, Store Procedures etc.
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */

class CF_ActiveRecords extends PDO
{

            private $selectfields = NULL,$from_where =NULL,$field_where = NULL,
                          $field_where_in =NULL,$from_where_in =NULL,$limit_value =NULL,
                          $offset_value =NULL,$field_name=NULL,$order_type = NULL;
            /** Variable $sql, query string to execute. */
           private $sqlqry,$debugqry,$last_id = NULL;
           private $where_type = NULL;

           private $_dbstatement = NULL;
           /** Variable $err_msg, always empty if not have errors. */
           private $_err = "";
           private $_closed = FALSE;

            function __construct($dsn, $username, $passwd)
            {
                parent::__construct($dsn, $username, $passwd);
           }


        /**
        * Escapes quotes in a string.
        *
        * @param string $string The string to be quoted.
        * @return string The string with any quotes in it properly escaped.
        */
        public function escape($string)
        {
                return $this->quote($string);
        }

            /**
            * Prevent cloning of DBmodel .
            *
            * @access public
            * @return void
            */
            public function __clone()
            {
                        // Issue E_USER_ERROR if clone is attempted
                        trigger_error('Cloning <em>DB ActiveRecords</em> is prohibited.', E_USER_ERROR);
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
                                                    //trigger_error('You are not allowed to use * for selecting all columns. Please use all instead of *', E_USER_ERROR)
                                                     GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', 'You are not allowed to use * for selecting columns. Please use "all" keyword instead of *.', __FILE__,$callee[0]['line'] ,TRUE)
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
                                    /*
                                    foreach($filedName as $key=>$value) :
                                        if( strpos($key,' ')===FALSE):
                                              GlobalHelper::display_errors(E_USER_NOTICE, 'Databse Error Occured','Please apply a single space between column name and conditional operators in where clause',$callee[0]['file'],$callee[0]['line'] ,'');
                                        endif;
                                        $columns = explode(' ',$key, 2);
                                       (is_string($value)) ? $whrvalue  = "'".$value."'" : $whrvalue = $value ;
                                       (is_string($key)) ? $columnkey  = "`".$columns[0]."` ".$columns[1]." " : $columnkey = '' ;
                                        $this->from_where .= " ".$columnkey.$whrvalue;
                                        $this->from_where .=  ($i < $arrCount-1) ? ' AND ' : '';
                                        $i++;
                                    endforeach;
                                    */
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

           private function extract_conditions($arr) {
                    $pattern  = '/([A-Za-z_]+[A-Za-z_0-9]?)[ ]?(!=|=|<=|<|>=|>|like|clike|slike|not|is|in|between|and|or)/';
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
                    if(empty($tblname))
                        trigger_error('Empty parameter given to '.__FUNCTION__.' method',E_USER_ERROR);

                    if($this->selectfields === NULL)
                            $this->selectfields= "*";

                    $limit =   (isset($this->limit_value)  && isset($this->offset_value)) ? " LIMIT ".$this->limit_value.",".$this->offset_value." "  :  '';
                    $orderby =   (isset($this->field_name)  && isset($this->order_type)) ? " ORDER BY ".$this->field_name."  ".$this->order_type  :  '';

                    $this->_processquery($tblname,$orderby,$limit);
                    try{
                            $this->_dbstatement = $this->prepare($this->sqlqry);
                            $this->_dbstatement->bindValue(':where',$this->from_where);
                            $this->_dbstatement->execute();
                    } catch(PDOException  $exception){
                              $this->_err = $exception;
                              //var_dump($this->_err->getTrace());
                             GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),$this->debugqry);
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
                                                $datas = $this->_dbstatement->fetchAll(PDO::FETCH_CLASS ,'cfDbDataReader');
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
                    if($this->_dbstatement->rowCount() > 0):
                        return $datas;
                    else:
                            return $this->num_row_count();
                    endif;
            }

            private function _processquery($tblname,$orderby,$limit)
            {
                 $searchedkey = strpos($this->from_where, "AND");

                    if($searchedkey === FALSE):
                                 ($this->field_where) ?  $where = '  WHERE  '.$this->field_where.' =  :where '    :  $where = ' ';
                                $this->debugqry = "SELECT ".$this->selectfields." FROM ".$tblname.'  WHERE  '.$this->field_where." = '".$this->from_where."'".$orderby.$limit;
                                 $this->sqlqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.$orderby.$limit;
                   else:
                                ($this->from_where !="") ? $where = " WHERE ".$this->from_where :  $where = "";
                                $this->debugqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.$orderby.$limit;
                                 $this->sqlqry = "SELECT ".$this->selectfields." FROM ".$tblname.$where.$orderby.$limit;
                    endif;

            }

            public function fetch_row($sql)
            {
                    $result = $this->_dbstatement->query($sql);
                    return $result->fetch();
            }

            public function num_row_count()
            {
                    return $this->_dbstatement->rowCount();
            }

            /**
            * Error Code del PDO
            * PDO error code
            *
            * @return unknown
            */
            public function get_error_code(){
                         return $this->_dbstatement->errorCode();
            }

            /**
            *  PDO error info
            * @return unknown
            */
            public function get_error_info()
            {
                      return $this->_dbstatement->errorInfo();
            }

                public function flushresult()
                {
                    if($this->is_closed() == FALSE):
                                $this->close();
                                $this->_closed=FALSE;
                    endif;
                }

                public function debug_query()
                {
                   //var_dump($this->get_error_info()); echo $this->_dbstatement->queryString;
                    // CF_AppRegistry::load('ErrorHandler')->handle_errors(E_USER_ERROR, '', __FILE__, $errorLine);
                        $callee = debug_backtrace();
                         $html = "";
                         $html  .= "<html> <head><title>Debug Query</title>
                                            <style type='text/css'>
                                              #contetainer { font-family:Lucida Grande,Verdana,Sans-serif; font-size:12px;padding: 20px 20px 12px 20px;margin:40px; background:#fff; border:1px solid #D3640F; }
                                            h2 { color: #990000;  font-size: 15px;font-weight: normal;margin: 5px 5px 5px 13px;}
                                            p {   margin:6px; padding: 9px; }
                                            </style>
                                            </head><body>";
                         $html .="<div id='contetainer'>";
                         $html .=" <h2> Debug Last Query </h2>";
                         $html .=" <p> Line Number : ".$callee[0]['line']."</p>";
                         $html .=" <p >Error Code : ".($this->get_error_code() === 00000)  ? '' : $this->get_error_code() ."</p>";
                         $html .=" <p> ".$this->debugqry."</p>";
                         $html .=" <p> Filename : ".$callee[0]['file']." </p>";
                         $html .="</div>";
                         $html .="</body></html>";
                         echo $html;
                }



                  /*
            * Insert Function to Insert Values in Database
            * Insert
            *
            * Comcfles an insert string and runs the query
            *
            * @access	public
            * @param	string	the table to retrieve the results from
            * @param	array	an associative array of insert values
            * @return	object
            */
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
                            $this->debugqry = "INSERT INTO `$tblName` ($fields) VALUES"." ($values)".";";
                              try{
                                    $this->_dbstatement = $this->prepare($this->sqlqry);
                                    $this->_dbstatement->execute();
                              } catch(PDOException  $exception){
                                      $this->_err = $exception;
                                      //var_dump($this->_err->getTrace());
                                     GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
                            }
                return TRUE;
            }


            public function last_inserted_Id()
            {
                $this->last_id = PDO::lastInsertId();
                 return $this->last_id;
            }
            /**
            * Update
            *
            * Comcfles an update string and runs the query
            *
            * @access	public
            * @param	string	the table to retrieve the results from
            * @param	array	an associative array of update values
            * @param	mixed	the where clause
            * @return	object
            */
            public function update($tblName="",$data=array())
            {

                    $qry = "";
                    $qry .="UPDATE ".$tblName." SET ";
                    $debugqry .="UPDATE ".$tblName." SET ";
                    $arrCount = count($data);
                    $i = 0;
                            foreach($data as $key=>$value) :
                                $qry .= " `".$key."` "."="." '".$value."'"." ";
                                $debugqry .= " `".$key."` "."="." '".$value."'"." ";
                                $qry .=  ($i < $arrCount-1) ? ',' : '';
                                $debugqry .=  ($i < $arrCount-1) ? ',' : '';
                                $i++;
                            endforeach;

                            $qry .=" WHERE ".$this->field_where." =  :column";
                            $debugqry .=" WHERE ".$this->field_where." = ".$this->from_where;

                            $this->sqlqry = $qry;
                            $this->debugqry = $debugqry;
                    try{
                        $this->_dbstatement = $this->prepare($this->sqlqry);
                        $this->_dbstatement->bindValue(':column',$this->from_where);
                        $this->_dbstatement->execute();
                    } catch(PDOException  $exception){
                          $this->_err = $exception;
                         GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
                    }
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
             public function delete($tblname)
            {
                   $this->sqlqry = "DELETE FROM ".$tblname." WHERE ".$this->field_where." = :where";
                    $this->debugqry = "DELETE FROM ".$tblname." WHERE ".$this->field_where." = ".$this->from_where;
                     try{
                            $this->_dbstatement = $this->prepare($this->sqlqry);
                            $this->_dbstatement->bindValue(':where',$this->from_where);
                            $this->_dbstatement->execute();
                    } catch(PDOException  $exception){
                              $this->_err = $exception;
                             GlobalHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
                    }
                return $return;
            }

            public function dblogerror()
            {
                   // var_dump($this->_err);


            }


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

            /**
            * @brief properties, print all drivers capables for the server. */
            public function drivers(){
                    print_r(PDO::getAvailableDrivers());
            }

            /**
            * @brief properties, print connection properties. */
            public function properties(){
                    echo "<span style='display:block;color:brown;border:1px solid chocolate;padding:2px 4px 2px 4px;margin-bottom:5px;'>";
                    print_r("<span style='color:#000'>Database :</span>&nbsp;".$this->getAttribute(PDO::ATTR_DRIVER_NAME)."&nbsp;".$this->getAttribute(PDO::ATTR_SERVER_VERSION)."<br/>");
                    print_r("<span style='color:#000'>Status :</span>&nbsp;".$this->getAttribute(PDO::ATTR_CONNECTION_STATUS)."<br/>");
                    print_r("<span style='color:#000'>Client :</span>".$this->getAttribute(PDO::ATTR_CLIENT_VERSION)."<br/>");
                    print_r("<span style='color:#000'>Information :</span>".$this->getAttribute(PDO::ATTR_SERVER_INFO));
                    echo "</span>";
            }

            /**
       *  This function is used to convert date to mysql
       *  @param date
       * @return mysql formate date
       */
        function date_to_mysql($input)
        {
                $output = false;
                $d = array_filter(preg_split('#[-/:. ]#', $input));

                if (is_array($d) && count($d) == 3) {
                        if (checkdate($d[1], $d[0], $d[2])) {
                                $output = "$d[2]-$d[1]-$d[0]";
                        }
                }else if(is_array($d) && count($d) == 2) {
                       $output = "$d[1]-$d[0]";
               }
                return $output;
        }

        /**
       *  This function is used to convert mysql date to general fomate of date
       *  @param date
       * @return mysql formate date
       */
        function mysql_to_date($input,$stringmonth = NULL)
        {
                $output = false;
                $d = array_filter(preg_split('#[-/:. ]#', $input));

                if (is_array($d) && (count($d) == 3 || count($d) == 6)) {
                        if (checkdate($d[1], $d[2], $d[0])) {
                                if($stringmonth){
                                        $stringmonth = date("M", mktime(0, 0, 0, ($d[1])));
                                        $output = "$d[2] $stringmonth $d[0]";
                                }
                                else
                                        $output = "$d[2]/$d[1]/$d[0]";
                        }
                } else if(is_array($d) && count($d) == 2) {
                           $output = "$d[1]/$d[0]";
                }
                return $output;
        }

        function __destruct() {
                unset($this->selectfields);
                unset($this->from_where);
                unset($this->field_where);
                unset($this->field_where_in);
                unset($this->limit_value);
                unset($this->field_name);
                unset($this->offset_value);
                unset($this->order_type);
                unset($this->sqlqry);
                unset($this->last_id);
                unset($this->_dbstatement);
        }

}
class cfDbDataReader { }
/* End of file DB_active_records.php */
/* Location: ./phcfgnite/core/database/DB_active_records.php */