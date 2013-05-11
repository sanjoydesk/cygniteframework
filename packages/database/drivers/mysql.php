<?php

/* =======================================================================*/
/* An open source application development framework for PHP 5.1.6 or newer 	  *
 * Database Connection Class                                                                                                              *
 * @package		PHP-ignite
 * @author		Mr. Sanjoy Dey
 * @copyright                                 Copyright (c)         .........
 * @license
 * @since		Version 1.0
 * @filesource
 *
 */
/* =======================================================================*/
interface IDBConnect
{
   public function connect($db_type,$host,$database,$user,$password,$port="");
   public function get_cnXn_obj($key);
}