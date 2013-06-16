<?php
/**
 * Files class that inherits Global base class and implements SecureData interface
 *
 * PHP Version 5.2 or newer
 *
 * @category     : PHP
 *
 * @name         : Files
 *
 * @author    	 : Cygnite Dev Team
 *
 * @Copyright    : Copyright (c) 2013 - 2014,
 * @License      : http://www.appsntech.com/license.txt
 * @Link	     : http://appsntech.com
 * @Since	     : Version 1.0
 * @Filesource
 * @Warning      : Any changes in this library can cause abnormal behaviour of the framework
 *
 */
class Files extends Globals implements SecureData {  public $_var = "_FILES";

	public function doValidation($key){
		array_walk_recursive($_FILES[$key],array('Files','clean'));
	}

	public static function clean($item,$key){

			if($key=="name")
			$item = basename($item);
			parent::__xss_clean($item, $key);

	}

}
