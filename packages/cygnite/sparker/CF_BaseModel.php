<?php
namespace Cygnite\Sparker;

use Cygnite\Database\CF_ActiveRecords;

if( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3  or newer.
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file license.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :   Sparker
 * @Filename                       :  CF_BaseModel
 * @Description                   :  This class is used to cygnite active records library
 * @Author                          :   Sanjoy Dey
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :   Copyright (c) 2013 - 2014,
 * @Brought you by           :    http://www.cygniteframework.com
 * @Since	                  :   Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class CF_BaseModel   //implements CF_IActiveRecords
{
        public $arr = array();
        private $db;
        private $connect;

        public function __construct($dbkey)
        {
            $this->db = new CF_ActiveRecords(is_string($dbkey) ? $dbkey : NULL);
            $this->connect = $dbkey;
        }

        public function __set($key,$value)
        {
             $this->arr[$key] = $value;
        }

        public function __get($key)
        {
            return $this->arr[$key];
        }

         public function save($tbl,$key =array())
        {
            if(empty($key))
                   return $this->db->insert($tbl,$this->arr);
            else
                  return $this->db->update($tbl,$this->arr,$key);
        }

        public function group_by($column)
        {
           return $this->db->group_by($column);
        }

        public function order_by($filed_name,$order_type="ASC")
        {
           return $this->db->order_by($filed_name,$order_type="ASC");
        }


        public function select($selecttype)
        {
            return $this->db->select($selecttype);

        }

        public function where($filedname,$where="",$type=NULL)
        {
            return $this->db->where($filedname,$where,$type=NULL);
        }

        public function limit($limit,$offset="")
        {
            return $this->db->limit($filedname,$where="",$type=NULL);
        }

        public function fetch_all($tblname,$fetchmode="")
        {
             return $this->db->fetch_all($tblname,$fetchmode="");
        }

        public function prepare_query($sql)
        {
            return $this->db->prepare_query($sql);
        }

        public function fetch_row($sql)
        {
            return $this->db->fetch_row($sql);
        }

        public function debug_query()
        {
           return $this->db->debug_query();
        }


        protected function flushresult()
        {
            return $this->db->flushresult();
        }

        public function explain()
        {
            return $this->db->explain_query();
        }

        public function remove($tblname,$whrarr = array())
        {
            return $this->db->remove($tblname,$this->arr);
        }

        public function close()
        {
            return $this->db->close();
        }

        public function __destruct()
        {
          unset($this->arr[$key]);
        }
}