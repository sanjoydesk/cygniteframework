<?php
/**
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
 * @Filename                       :  SQLUtilities
 * @Description                   :  This SQLUtilities used to take care of your external mysql queries and functionalities
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class DB_SQLUtilities
{
    private static $_dbstmt,$_dbgeneratorobj;
    private static $from_where,$field_where,$where_type,$where_array = array();

    public function setdbstmt($val,$qryobj)
    {
        self::$_dbstmt = $val;
        self::$_dbgeneratorobj = $qryobj;
    }

    public static function getdbstmt()
    {
         if(!is_null(self::$_dbstmt))
            return self::$_dbstmt;

         return FALSE;
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
     public static function whereclause($filedName,$where="",$type=NULL)
    {
                // Check whether value passed as array or not
               if(is_array($filedName)):
                         $arrCount = count($filedName);

                            $result_array = self::extract_conditions($filedName);
                            $arrCount = count($result_array);
                            $i = 0;
                            $where = $whrvalue = $whrcondition= "";
                                foreach($result_array as $row):
                                      (is_string($row['2'])) ? $whrvalue  = " '".$row['2']."'" : $whrvalue = $row['2'] ;
                                      (is_string($row['1'])) ? $whrcondition  = strtoupper($row['1']) : $whrcondition = $row['1'] ;
                                     self::$from_where .= "`".$row['0']."`"." ".$whrcondition.$whrvalue;
                                     self::$from_where .=       ($i < $arrCount-1) ? ' AND ' :  '';
                                    $i++;
                                endforeach;

                           return $this;
               endif;

             if(is_string($filedName))
                 $filedName = "`".$filedName."`";

            self::$field_where = $filedName;
            self::$from_where = $where;
            if(!is_null($type))
                self::$where_type  = $type;
           self::$where_array = array(
                        'field_where' => self::$field_where,
                        'from_where' => self::$from_where,
                        'where_type' => self::$where_type
                   );

           return self::$where_array;
    }

    private static function extract_conditions($arr) {
            $pattern  = '/([A-Za-z_]+[A-Za-z_0-9]?)[ ]?(!=|=|<=|<|>=|>|like|clike|slike|not|is|in|between|and|or|LIKE|AND|OR)/';
            $result = array();
            foreach($arr as $key => $value) :
                    preg_match($pattern, $key, $matches);
                    $result []= array($matches[1], $matches[2], $value);
            endforeach;
         return $result;
    }

    public static function get_generater_instance()
    {
        if(!is_null(self::$_dbgeneratorobj))
        return self::$_dbgeneratorobj;

        return FALSE;
    }



    public static function num_row_count()
    {
       return self::$_dbstmt->rowCount();
    }

    /**
    * Error Code del PDO
    * PDO error code
    *
    * @return unknown
    */
    public static function get_error_code()
    {
                 return self::$_dbstmt->errorCode();
    }

    /**
    *  PDO error info
    * @return unknown
    */
    public static function get_error_info()
    {
              return self::$_dbstmt->errorInfo();
    }

    public static function debugqry($query,$err_code)
    {  // var_dump(self::$_dbstmt->errorCode());
        //var_dump(self::getdbstmt()->errorCode());
        //var_dump($err_code->errorCode());

        $callee = debug_backtrace();
        //show($callee,'exit');
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
        $html .=" <p> Line Number : ".$callee[1]['line']."</p>";
        $html .=" <p >Error Code : ".(self::getdbstmt()->errorCode() === 00000)  ? '' : self::getdbstmt()->errorCode() ."</p>";
        $html .=" <p> ".$query."</p>";
        $html .=" <p> Filename : ".$callee[1]['file']." </p>";
        $html .=" <p> Method Name : ".$callee[1]['function']."()  </p>";
        $html .="</div>";
        $html .="</body></html>";
        echo $html;//exit;
    }

    protected function test()
    {
        echo "Test here ";exit;
    }

    /**
    * @brief properties, print all drivers capables for the server. */
    public function drivers()
    {
            print_r(PDO::getAvailableDrivers());
    }

    /**
    * @brief properties, print connection properties. */
    public function properties()
    {
            echo "<span style='display:block;color:brown;border:1px solid chocolate;padding:2px 4px 2px 4px;margin-bottom:5px;'>";
            print_r("<span style='color:#000'>Database :</span>&nbsp;".self::get_generater_instance()->getAttribute(PDO::ATTR_DRIVER_NAME)."&nbsp;".self::get_generater_instance()->getAttribute(PDO::ATTR_SERVER_VERSION)."<br/>");
            print_r("<span style='color:#000'>Status :</span>&nbsp;".self::get_generater_instance()->getAttribute(PDO::ATTR_CONNECTION_STATUS)."<br/>");
            print_r("<span style='color:#000'>Client :</span>".self::get_generater_instance()->getAttribute(PDO::ATTR_CLIENT_VERSION)."<br/>");
            print_r("<span style='color:#000'>Information :</span>".self::get_generater_instance()->getAttribute(PDO::ATTR_SERVER_INFO));
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

                if (is_array($d) && count($d) == 3)
                {
                        if (checkdate($d[1], $d[0], $d[2]))
                        {
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
   }