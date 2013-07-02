<?php
/*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                        :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */

     class CF_BaseModel extends CF_DBConnect
    {
        public static $appsmodel,$dbobj;
       public  function __construct()
        {
                 parent::cnXN();
               // var_dump(parent::get_cnXn_obj('test'));
        }

        public static function get_instance()
        {
                if(is_null(self::$appsmodel)):
                       return new self();
                endif;
        }

        public static function get_db_instance($key)
        {
            if(is_null(self::$dbobj)):
                  return self::get_instance()->get_cnXn_obj($key);
            endif;
                return FALSE;

       }
         function __destruct()
         {
              //  parent::flushcnXn();
        }
    }