<?php
namespace Cygnite\Libraries;

use Cygnite\Helpers\GHelper as GHelper;

/**
 * Request class that inherits Global base class and implements ISecureData interface
 *
 * PHP Version 5.2 or newer
 *
 * @category     : PHP
 *
 * @name         : Request
 *
 * @author    	 : Cygnite Dev Team
 *
 * @Copyright    : Copyright (c) 2013 - 2014,
 * @License      : http://www.cygniteframework.com/license.txt
 * @Link	     : http://appsntech.com
 * @Since	     : Version 1.0
 * @Filesource
 * @Warning      : Any changes in this library can cause abnormal behaviour of the framework
 *
 */
class Request extends Globals implements ISecureData
{
    public $_var = "_REQUEST";
    /**
    *
    * @var string
    */
    private $_param; // the returned POST/GET values

    public function values($key, $default = NULL)
    {
            if( isset($_POST[$key]) ):
                    $this->_param = $_POST[$key];
            else:
                    if( isset($_GET[$key]) )
                        $this->_param = $_GET[$key];
                    else
                        $this->_param = $defvalue;
            endif;
            if( empty($this->_param) ) $this->_param = $default;
       return $this->clean_variables($this->_param);
    }

}