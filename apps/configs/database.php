<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *  Database Configuration Settings
 *
 *  An open source application development framework for PHP 5.3x or newer
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
 *@package                         :  Apps
 *@subpackages                :  Configurations
 *@filename                        :  database.php
 *@description                    : You can set your database configurations here.
 *@author                           : Sanjoy Dey
 *@copyright                      :  Copyright (c) 2013 - 2014,
 *@link	                  :  http://www.cygniteframework.com
 *@since	                 :  Version 1.2
 *@filesource
 *@warning                      :  If you don't protect this directory from direct web access, anybody will be able to see your database configuaration and settings.
 *
 *
 */



 /*
  * ----------------------------------------------------------------------------
  * Set Database Configuration
  * ----------------------------------------------------------------------------
  *
  * To connect Multiple database connection use db2,db3.... etc
  * @prototype set database hostname
  * @prototype set database username
  * @prototype set database password
  * @prototype set database name
  * @prototype set database prefix
  * @prototype set database type (mysql,pg,oracle)
  * @prototype set database persistance connection TRUE or FALSE
  */
return array(
                       'db' =>array(
                                    'hostname' => 'localhost',
                                    'username'  => 'root',
                                    'password'  => '',
                                    'dbname'    => 'cygnite',
                                    'dbprefix'  => '',
                                    'dbtype'    => 'mysql',
                                    'port'        => '',
                                    'pconnection' =>TRUE
                    )
               /*   ,'db2' => array(
                                    'hostname' => 'localhost',
                                    'username'  => 'root',
                                    'password'  => '',
                                    'dbname'    => 'hris',
                                    'dbprefix'  => '',
                                    'dbtype'    => 'mysql',
                                    'port'        => '',
                                    'pconnection' =>TRUE
                    ) */

);