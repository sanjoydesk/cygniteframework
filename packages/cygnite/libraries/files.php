<?php
namespace Cygnite\Libraries;

/**
 * Files class that inherits Global base class and implements ISecureData interface
 *
 * PHP Version 5.3 or newer
 *
 * @category     : PHP
 *
 * @name         : Files
 *
 * @author    	 : Cygnite Dev Team
 *
 * @Copyright    : Copyright (c) 2013 - 2014,
 * @License      : http://www.cygniteframework.com/license.txt
 * @Link	     : http://www.cygniteframework.com
 * @Since	     : Version 1.0
 * @Filesource
 * @Warning      : Any changes in this library can cause abnormal behaviour of the framework
 *
 */
class Files extends Security
{
    public $_var;

    public function doValidation($key)
    {
        array_walk_recursive($_FILES[$key], array('Files', 'clean'));
    }

    public static function clean($item, $key)
    {
        if ($key == "name") {
            $item = basename($item);
        }

        parent::__xss_clean($item, $key);
    }
}
