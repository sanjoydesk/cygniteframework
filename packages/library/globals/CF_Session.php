<?php
class Session extends Globals implements SecureData{  public $_var = "_SESSION";

 	public function __construct(){
 	ini_set("session.use_only_cookies",1);
 	$this->set_gc_probability(1);
 	session_start();
 	session_regenerate_id();
 	$keys = array('HTTP_USER_AGENT', 'SERVER_PROTOCOL',
				  'HTTP_ACCEPT_CHARSET', 'HTTP_ACCEPT_ENCODING', 'HTTP_ACCEPT_LANGUAGE');
	$tmp = '';
	foreach ($keys as $v) {
		if (isset($_SERVER[$v])) $tmp .= $_SERVER[$v];
	}
	$browser_sig = md5($tmp);
 	if (empty($_SESSION)) { // new session
		$_SESSION['log'] = md5($browser_sig);
		} else if ($_SESSION['log'] != md5($browser_sig)) {
		session_destroy(); // destroy fake session
		session_start(); // create a new “clean” session
		}
	if (!empty($_SERVER['HTTP_REFERER'])) {
		$url = parse_url($_SERVER['HTTP_REFERER']);
		
 		if ($url['host'] != $_SERVER['HTTP_HOST']) {
			session_destroy(); // destroy fake session
 		}
		}
	}
	
	private function set_gc_probability($value)
    {
          $value = (int)$value;
          if($value>=0 && $value<=100):
                ini_set('session.gc_probability',$value);
                ini_set('session.gc_divisor','100');
          endif;
    }
	
	
	
 
 public function __destruct(){}

	
}