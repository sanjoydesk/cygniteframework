<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Documentation of Phcfgnite Framework
 *
 * @author SANJOY
 */

/*==========================================================================================================
 * Error Handling Mechanisam
 *Error Types : E_USER_ERROR,E_USER_WARNING,E_USER_NOTICE
 *GlobalHelper::display_errors('Error Type', 'Error Title','Error Message','Error Filename','Error Line number' ,'TRUE if E_USER_ERROR Else NULL');
 * ==========================================================================================================
 */



/*==========================================================================================================
 * Database Documentation
 *@Active Records Class
 *@Table Name used : phcfgnite
 * @to do: like,where in, or where, left join,right join,inner join, insert,update,delete, and other sql functions
 *
 * ==========================================================================================================
 */

/*
 * Database type : array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");
 */

/*
 *Table name given in config file acts as object to perform database operations
 *User can call database functions. Example : phcfgnite is the tablename.
 * $this->phcfgnite->where('username','Sanjay');
 * $this->phcfgnite->order_by('id','DESC');
 * $this->phcfgnite->limit(3);
 * $this->phcfgnite->select('username,country');
 * $resultArray = $this->phcfgnite->fetch_all('user_details');
 *
 */



/*
 *The where() function
 * Where clause accepts parameters either as array where($array) or where(column name,value)
 *Example usage :$this->phcfgnite->where('username','Sanjoy Dey'); OR
 *$where = array(
                            'username =' => 'Sanjoy Dey',
                            'id >'=> '4'
                    );
 * $this->phcfgnite->where($where);
 * [NB: Need to give a space between column name and conditional operators in array]
 * If array has given in where condition then it takes as multiple condition as example:
 * WHERE `username` = 'Sanjoy Dey' AND `id` > 4
 *
 */


/*
 * The order_by() function
 * Order by function accepts first parameter as column name and second parameter as ASC or DESC
 *By default order type as ASC
 *Throws error if empty parameter given
 * $this->phcfgnite->order_by('id');               ( i.e )  ORDER BY id ASC
 * $this->phcfgnite->order_by('id','DESC');  ( i.e )  ORDER BY id DESC
 */

/*
 * The limit() function
 *Limit function accepts two parameters. First parameter as limit and second parameter as offset.
 *If single parameter passed (i.e) second parameter passed empty then by default it takes 0  to limit value.
 *Throws error if empty parameter passed on given function
 *Example usage :
 * $this->phcfgnite->limit(3);     i.e LIMT 0,3
 *$this->phcfgnite->limit(1,3);  i.e LIMIT 1,3
 */

/*
 *The select() function
 *Select function accept all columns passed as string and to select all columns of the table need to pass "all" keyword inside select function.
 * Throws an error if * passed inside the function.
 * Example usage:
 *$this->phcfgnite->select('username,country'); (i.e) SELECT username,country FROM tablename;
 * OR
 * $this->phcfgnite->select('all');   (i.e) SELECT * FROM tablename; to select all fields of the table *
 */


/*
 * The fetch_all() function
 *
 * The fetch_all() function should be call after all condition. This function giving security user input since PDO hasbeen used for binding values
 *
 * By default fetch_all function will return associative array as result
 * User can pass the fetch type as second parameter. Different types of fetch modes this framework allows like -
 * Fetch Modes : FETCH_ASSOC,FETCH_GROUP,FETCH_BOTH,FETCH_CLASS,FETCH_OBJECT,FETCH_COLUMN
 *The above function returns result as array by default if values available in table else it returns row count as 0
 *Example usage:  $this->table_name->fetch_all('guestbook');
 */

/*
 * The debug_query() function
 *The debug_query() function debug the last query executed by user
 *It display the line number and filename and query
 *Example usage: $this->phcfgnite->debug_query();
 *
 */

/*
 *The flushresult() function
 *It used to clear up the memory of the query executed. It need to be call after fetch_all() function
 * Example usage : $this->phcfgnite->flushresult();
 */

/*
 *The num_row_count() function
 *The num_row_count() function used to get the row count of the query executed
 *Example usage : $this->phcfgnite->num_row_count();
 *
 */

/*==========================================================================================================
 * Database documentation end
 * ==========================================================================================================
 */



