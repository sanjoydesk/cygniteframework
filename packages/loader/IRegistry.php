<?php
/*
 * ===========================================================================
 *  An open source application development framework for PHP 5.1.6 or newer
 *
 * Load class is to load requested database and view files
 * @access public
 * @Author          :         Sanjoy Dey
 * @Modified By :
 * @Warning      :         Any changes in this file can cause abnormal behaviour of the framework
 * @Developed By  :         PHP-ignite Team
 * ===========================================================================
 */

interface IRegistry
{
       public static function initialize($dirRegistry = array());

       public  function apps_load($key);
}