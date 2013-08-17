<?php
namespace cygnite\libraries\globals;
/**
 * Globals class a base class to handle all global variables.this class implements ArrayAccess
 * to use object as an array
 *
 * PHP Version 5.2 or newer
 *
 * @category  PHP
 *
 * @author    Cygnite Dev Team
 *
 * @name         : Globals
 * @Copyright    : Copyright (c) 2013 - 2014,
 * @License      : http://www.cygniteframework.com/license.txt
 * @Link	 : http://appsntech.com
 * @Since	 : Version 1.0
 * @Filesource
 * @Warning      : Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 * @todo : need to add Cookie settings
 *
 *
 */
class globals implements \ArrayAccess
{

    /**
     * @var array $_array will hold validated global variables
     *
     */

    private $_array = array();

    public function __construct(){}

    /**
     * __set() Magic method sets the value for given in Global array and $_array
     * @param string $key
     * @param string $value
     *
     */

    public function __set($key,$value)
    {
        $this->_array[$key] = $value;
        $GLOBALS[$this->_var][$key] = $value;
    }

    /**
     * __get() Magic method return value with necessary validation for requested key in Global variables
     * if key string does not exists in Gloabal array error will be triggered
     * @param  string $key
     * @return any    type
     *
     */

    public function __get($key)
    {
        if(isset($this->_array[$key])):
            return $this->_array[$key];
        elseif(isset($GLOBALS[$this->_var][$key])):
            $this->doValidation($key);
            $this->_array[$key] = $GLOBALS[$this->_var][$key];

            return $this->_array[$key];
        else:
        trigger_error("Undefined index : {$key} ",E_USER_NOTICE);
        endif;

    }

    /**
     * Determines wheather the key exists or not in Global array
     * @param  string  $key
     * @return boolean true if exists else false
     *
     */

    public function __isset($key){ return isset($GLOBALS[$this->_var][$key]); }

    public function __unset($key){ unset($GLOBALS[$this->_var][$key]); }

    public function offsetExists($offset){}

    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    public function offsetSet($offset, $value)
   {

        if(is_null($offset))
            $GLOBALS[$this->_var][] = $value;
        else
             $GLOBALS[$this->_var][$offset] = $value;
    }

    public function offsetUnset($offset)
    {

        unset($this->{$offset});
    }

    public static function __xss_clean(&$item,&$key)
   {

        $item = htmlspecialchars($item,ENT_QUOTES);
        $item = preg_replace_callback('!&amp;#((?:[0-9]+)|(?:x(?:[0-9A-F]+)));?!i',array(__CLASS__,'decode'), $item); // PERL
        $item = preg_replace(
                                            '!<([A-Z]\w*)
                                            (?:\s* (?:\w+) \s* = \s* (?(?=["\']) (["\'])(?:.*?\2)+ | (?:[^\s>]*) ) )*
                                            \s* (\s/)? >!ix',
                                            '<\1\5>', strip_tags(html_entity_decode($item)));
    }

    public static function decode($matches)
    {
            if(!is_int($matches[1]{0}))
                   $val = '0'.$matches[1]+0;
            else
                  $val = (int) $matches[1];

            if($val>255)

                   return '&#'.$val.';';

            if($val >= 65 && $val <= 90  //A-Z
               || $val >= 97 && $val <= 122 // a-z
               || $val >= 48 && $val <= 57) // 0-9

               return chr($val);

               return $matches[0];

    }

    public function doValidation($key)
    {
        if(is_array($GLOBALS[$this->_var][$key]))
               array_walk_recursive($GLOBALS[$this->_var][$key],array(__CLASS__,'clean'));
        else
               self::__xss_clean($GLOBALS[$this->_var][$key], $key);
    }

    public static function clean($item,$key)
   {
              self::__xss_clean($item, $key);
    }

        protected function clean_variables($value)
        {
                    if( empty($value) ) return $value;
                    if( is_null($value) ) return $value;
                    if( is_string($value) ):
                            $spec = array('/[\']/', '/--/', '/\bdrop\b/i', '/\bdelete\b/i', '/\binsert\b/i', '/\bupdate\b/i');
                            $value = preg_replace($spec, "", $value);

                            return $value;
                    endif;

                return $value;
        }

    public function __destruct(){}
}
