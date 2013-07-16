<?php
/**
 * Get class that inherits Global base class and implements ISecureData interface
 *
 * PHP Version 5.3X or newer
 *
 * @category     : PHP
 *
 * @name         : Get
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
class CF_Get extends CF_Globals implements ISecureData
{
    public $_var = "_GET";
    /**
    *
    * @var string
    */
    private $_param; // the returned GET values

    public function doValidation($key){}

           /**
             * Set new value to given GET variable.
             * @param string $variable Get variable name.
             * @param mixed $value     New value to be set.
             */
            public static function save( $variable, $value="")
            {
                  $_GET[$variable] = $value;
                  if(is_array($variable)):
                       foreach($variable as $key=>$val):
                                $_GET[$key] = $this->clean_variables($val);
                       endforeach;
                endif;
            }

             public function  values($key, $default =NULL)
            { var_dump($_GET);
                    $this->_param = ( isset($_GET[$key]) ? $_GET[$key] : $default );
                    //$this->_clean($value);
                    return $this->clean_variables($this->_param);
            }

            public function query()
            {
                    $get_result = array();
                    foreach($_GET as $key => $val)
                            $get_result[] = $key ."=". $val;

                    return implode("&", $get_result);
            }

}