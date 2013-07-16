<?php

class CF_SQLUtilities
{
    private static $_dbstmt,$_connection;

    public function setdbstmt($val,$qryobj)
    {
        self::$_dbstmt = $val;
        self::$_connection = $qryobj;
    }

    public static function getdbstmt()
    {
         if(!is_null(self::$_dbstmt))
            return self::$_dbstmt;

         return FALSE;
    }

    public static function get_generater_instance()
    {
        if(!is_null(self::$_connection))
        return self::$_connection;

        return FALSE;
    }

    public function num_row_count()
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
    {
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
        $html .=" <p> Line Number : ".$callee[1]['line']."</p>";
        $html .=" <p >Error Code : ".(self::$_dbstmt->errorCode() === 00000)  ? '' : self::$_dbstmt->errorCode() ."</p>";
        $html .=" <p> ".$query."</p>";
        $html .=" <p> Filename : ".$callee[1]['file']." </p>";
        $html .=" <p> Method Name : ".$callee[1]['function']."()  </p>";
        $html .="</div>";
        $html .="</body></html>";
        echo $html;//exit;
    }


    /**
    * @brief properties, print all drivers capables for the server. */
    public function drivers()
    {
            show(PDO::getAvailableDrivers());
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