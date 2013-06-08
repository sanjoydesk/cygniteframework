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
 * @Filename                       :  SQLManapulator
 * @Description                   :  This SqlManapulator will take care of your insert,update,delete,drop queries.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    include_once 'IDBSQLProcessor'.EXT;
    include_once 'DB_SQLUtilities'.EXT;
    //extends DB_SQLUtilities implements IDBSQLProcessor
    class DB_SQLManapulator
    {
        private $dbstatement,$pdo,$_err;
        private $sqlqry,$debugqry,$from_where,$field_where,$where_type;

        function __construct($object = NULl)
        {
            $this->pdo = $object;
        }

        public function create_table()
        {

        }

        public function delete_table()
        {

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
              /*echo "<pre>";
              var_dump($this->pdo);
              exit;*/

                try{

                      $this->dbstatement = $this->pdo->prepare($this->sqlqry);
                      DB_SQLUtilities::setdbstmt($this->dbstatement,$this->pdo);
                      $this->dbstatement->execute();
                } catch(PDOException  $exception){
                        $this->_err = $exception;
                        //echo "<pre>";print_r($this->_err->getTrace());
                       GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
              }
          return TRUE;
      }


      public function last_inserted_id()
      {
           return $this->pdo->lastInsertId();
      }

      public function where($filed_name,$where="",$type=NULL)
      {
          $where = DB_SQLUtilities::whereclause($filed_name, $where, $type);

          $this->field_where = $where['field_where'];
          $this->from_where = $where['from_where'];
          $this->where_type = $where['where_type'];

          return $this;
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
                      $this->debugqry = $debugqry; //exit;
              try{
                  $this->dbstatement = $this->pdo->prepare($this->sqlqry);
                  DB_SQLUtilities::setdbstmt($this->dbstatement,$this->pdo);
                  $this->dbstatement->bindValue(':column',$this->from_where);
                  $this->dbstatement->execute();
              } catch(PDOException  $exception){
                    $this->_err = $exception;
                   GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
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
             echo $this->debugqry = "DELETE FROM ".$tblname." WHERE ".$this->field_where." = ".$this->from_where;
               try{
                      $this->dbstatement = $this->pdo->prepare($this->sqlqry);
                      DB_SQLUtilities::setdbstmt($this->dbstatement,$this->pdo);
                      $this->dbstatement->bindValue(':where',$this->from_where);
                      $this->dbstatement->execute();
              } catch(PDOException  $exception){
                        $this->_err = $exception;
                       GHelper::display_errors(E_USER_ERROR, 'Database Error Occured', $this->_err->getMessage(), __FILE__, $this->_err->getLine(),TRUE);
              }
          return $return;
      }

      public function debug_query()
      {
           return DB_SQLUtilities::debugqry($this->debugqry,$this->dbstatement);
      }

      public function __destruct()
      {
          unset($this->_err);
          unset($this->dbstatement);
          unset($this->sqlqry);
      }

    }