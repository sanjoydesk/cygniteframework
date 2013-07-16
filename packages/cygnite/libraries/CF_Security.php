<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : CF_Security
 * @Description                   : Security package : GLOBAL variables will be accesed securly through Security package.
 *                                              This package provides necessary in built validation for users data.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
Cygnite::import(CF_SYSTEM.'>cygnite>libraries>globals>CF_Globals');// includes GLOBAL class
Cygnite::import(CF_SYSTEM.'>cygnite>libraries>globals>ISecureData'); // includes ISecureData Interface
/**
 *
 *  Security class creates instance for a class thats inheriting GLOBALS class and implementing
 *  ISecureData interface and holds instances in register_global array
 *
 *  @todo need to more validation or data filter methods
 *
 *  @name Security
 *
 */

class CF_Security
{

	public $register_global = array(); // Array to object instances

	public function __construct(){}

	/**
	 *  __get() Magic method gets class name as a parameter and determines wheather instance exists
	 *  for incoming key value(class name) or  not.
	 *  if instance exists in register_global array instance will be returned
	 *  otherwise if requested class exists new instance will be created, stored in register_global array
	 *  and returned
	 *
	 *  @access public
	 *
	 *  @method __get($key)
	 *
	 *  @param  string  $key class name to create instance
	 *
	 *  @return Globals $instance
	 */

	public function __get($key)
	{
		$key = ucwords($key);
		if(isset($this->register_global[$key])): // determines whether requested instance exists
			return $this->register_global[$key];
		elseif(class_exists($key)): // determines whether requested class included into package
			return $this->register_global[$key] = new $key;
		endif;
	} // end __get magic method

	/**
	 * This method escapes quotes existed in incoming string
	 *
	 * @access public
	 *
	 * @method escape
	 *
	 * @param  string $data string value need to escape quotes
	 *
	 * @return string quotes escaped string
	 */

	public function escape($data)
	{

		return "'".addslashes($data)."'";

	} // end escape method

	/**
	 * This method escapes quotes existed in incoming string that will be used in SQL like operator
	 *
	 * @access public
	 *
	 * @method escape
	 *
	 * @param string $data string value need to escape quotes while using SQL like operator
	 *
	 * @return string quotes escaped string
	 */

	public function escape_for_like($data)
	{

		return "'".addcslashes(addslashes($data), "%_")."'";
	} // end escape_for_like method

	public function __destruct(){ }

} // end security class