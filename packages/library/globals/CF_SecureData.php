<?php

/**
 * Files class that inherits Global base class and implements SecureData interface
 * 
 * PHP Version 5.1.6 or newer
 * 
 * @category     : PHP
 * 
 * @name         : SecureData
 * 
 * @author    	 : Balamathankumar<balamathankumar@gmail.com>
 * 
 * @Copyright    : Copyright (c) 2013 - 2014,
 * @License      : http://www.appsntech.com/license.txt
 * @Link	 : http://appsntech.com
 * @Since	 : Version 1.0
 * @Filesource
 * @Warning      : Any changes in this library can cause abnormal behaviour of the framework
 * 
 * @todo : need to add more validation or data filter methods
 */
interface SecureData { 
	
	
    public function __set($key,$value);
	
    public function __get($key);
	
    public function __isset($key);

    public function __unset($key);

    public function doValidation($key);

}
