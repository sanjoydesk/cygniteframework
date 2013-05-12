<?php
class Globals implements ArrayAccess{
	
	private $_Array = array();
	
	public function __construct(){}
	
    public function __set($key,$value){
    	
    	$this->_Array[$key] = $value;
    	$GLOBALS[$this->_var][$key] = $value;
    }
	
    public function __get($key){ 
    	
    	
    	if(isset($this->_Array[$key])):
    		return $this->_Array[$key];
    	elseif(isset($GLOBALS[$this->_var][$key])):
    	    $this->doValidation($key);
    	    $this->_Array[$key] = $GLOBALS[$this->_var][$key]; 
    		return $this->_Array[$key];
    	else:
    	trigger_error("Undefined index : {$key} ",E_USER_NOTICE);
    	endif;
    	
    }
	
    public function __isset($key){ return isset($GLOBALS[$this->_var][$key]); }
	
	public function __unset($key){ unset($GLOBALS[$this->_var][$key]); }
	
    public function offsetExists($offset){}
	
	public function offsetGet($offset){
		
		return $this->{$offset};
	
	}
	
	public function offsetSet($offset, $value){
		
		if(is_null($offset))
		 $GLOBALS[$this->_var][] = $value;
		else
		 $GLOBALS[$this->_var][$offset] = $value;
	}
	
	public function offsetUnset($offset){
		
		unset($this->{$offset});
	}
	
	public static function __xss_clean(&$item,&$key){
		
		 $item = htmlspecialchars($item,ENT_QUOTES);
		 
		 $item = preg_replace_callback('!&amp;#((?:[0-9]+)|(?:x(?:[0-9A-F]+)));?!i',array(__CLASS__,'decode'), $item); // PERL
		  
		 $item = preg_replace(
							'!<([A-Z]\w*)
							(?:\s* (?:\w+) \s* = \s* (?(?=["\']) (["\'])(?:.*?\2)+ | (?:[^\s>]*) ) )*
							\s* (\s/)? >!ix',
							'<\1\5>', strip_tags(html_entity_decode($item)));
	}
	
	public static function decode($matches){
		
		
		if(!is_int($matches[1]{0})){
			
				$val = '0'.$matches[1]+0;	
		}else{
			
			    $val = (int)$matches[1];
		}
		
		if($val>255)
		          return '&#'.$val.';';
		          
		if($val >= 65 && $val <= 90  //A-Z
		   || $val >= 97 && $val <= 122 // a-z
		   || $val >= 48 && $val <= 57) // 0-9 
		   return chr($val);
		   
		   return $matches[0];
		
	}
	
	
    public function doValidation($key){
		
		if(is_array($GLOBALS[$this->_var][$key]))
    		array_walk_recursive($GLOBALS[$this->_var][$key],array(__CLASS__,'clean'));
    	else 
    		self::__xss_clean(&$GLOBALS[$this->_var][$key], &$key);
	}
	
	public static function clean(&$item,&$key){
	
		
		self::__xss_clean(&$item, &$key);
	}
	
	
	
	public function __destruct(){}


}