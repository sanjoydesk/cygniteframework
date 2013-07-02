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
 * @Filename                       :  MySqlAdapter
 * @Description                   :  This MySqlAdapter is used to decide which types of libraries to include and queries to execute.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
      include_once 'PDOConnector'.EXT;

     class DB_MySQLAdapter extends PDOConnector
     {
            protected $dns;
            protected $username;
            protected $password;
            public $db = NULL;


            public function __construct($dns, $username, $password)
            {
                    $this->dns = $dns;
                    $this->username = $username;
                    $this->password = $password;
                    parent::connect_db($this->dns,$this->username, $this->password, array(PDO_ATTR_PERSISTENT => true));
                    // var_dump(debug_print_backtrace()); //exit;
            }

            public static function get_adopter_instance()  { }

             /**
            * Retrieve value with constants being a higher priority
            * @param $key Key to get
            */
          /*  public function __get( $key )
            {
               if(isset( $this->constants[$key])) {
                   return $this->constants[$key];
               }
            } */

            /**
            * Set a new or update a key / value pair
            * @param $key Key to set
            * @param $value Value to set
            */
         /*   public function __set($key,$value)
            {
               $this->constants[$key] = $value;
            }
            */

            private function _get_instance($class)
            {
                    if(is_null($this->db) && class_exists($class))
                       $this->db = new $class($this);

                    return $this->db;
            }

            public function sql_generator($manapulator,$flag = FALSE)
            {
                  //$this->manapulate = (bool)$flag;
                    if($flag == FALSE) $this->db = NULL;

                    if($manapulator == 'dbfetch' && $flag === TRUE):
                                 include_once DATABASE_PREFIX.'Selections'.EXT;
                                if(is_null($this->db) && class_exists('DB_Selections'))
                                    $this->db = new DB_Selections($this);

                                return (!is_null($this->db)) ?
                                            $this->db
                                        : trigger_error('Cannot intentiate Sql Selectors',E_USER_WARNING);
                    else:
                                include_once DATABASE_PREFIX.'SQLManapulator'.EXT;
                              if(is_null($this->db) && class_exists('DB_SQLManapulator'))
                                    $this->db = new DB_SQLManapulator($this);
                             return (!is_null($this->db)) ?
                                        $this->db
                                    : trigger_error('Cannot intentiate Sql Manapulor',E_USER_WARNING);
                    endif;
            }

            function __destruct()
            {
                    unset($this->db);
            }

     }