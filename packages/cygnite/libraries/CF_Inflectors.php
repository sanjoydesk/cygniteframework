<?php  if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
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
 * @Sub Packages               :  Library
 * @Filename                       : CF_Inflectors
 * @Description                   : This library will be avaialble on next version
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class CF_Inflectors
    {
           /********************* Inflectors ******************/



	/**
	 * camelCaseAction name -> dash-separated.
	 * @param  string
	 * @return string
	 */
	private static function actionpath($s)
	{
		$s = preg_replace('#(.)(?=[A-Z])#', '$1-', $s);
		$s = strtolower($s);
		$s = rawurlencode($s);
		return $s;
	}



	/**
	 * dash-separated -> camelCaseAction name.
	 * @param  string
	 * @return string
	 */
	private static function pathaction($s)
	{
		$s = strtolower($s);
		$s = preg_replace('#-(?=[a-z])#', ' ', $s);
		$s = substr(ucwords('x' . $s), 1);
		//$s = lcfirst(ucwords($s));
		$s = str_replace(' ', '', $s);
		return $s;
	}



	/**
	 * PascalCase:Presenter name -> dash-and-dot-separated.
	 * @param  string
	 * @return string
	 */
	private static function controllerpath($s)
	{
		$s = strtr($s, ':', '.');
		$s = preg_replace('#([^.])(?=[A-Z])#', '$1-', $s);
		$s = strtolower($s);
		$s = rawurlencode($s);
		return $s;
	}



	/**
	 * dash-and-dot-separated -> PascalCase:Presenter name.
	 * @param  string
	 * @return string
	 */
	private static function pathview($s)
	{
		$s = strtolower($s);
		$s = preg_replace('#([.-])(?=[a-z])#', '$1 ', $s);
		$s = ucwords($s);
		$s = str_replace('. ', ':', $s);
		$s = str_replace('- ', '', $s);
		return $s;
	}

    }