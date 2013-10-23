<?php
namespace Cygnite;

use Cygnite\Security;
use InvalidArgumentException;
use Closure;

class Input
{
    public $except;

    private $security;

    public $request = array();

    private static $instance;

    private $cookieInstance;

    private $cookie;

    private function __construct()
    {
        $this->security = Security::instance(
            function ($instance) {
                return $instance;
            }
        );
        $this->request = $this->getRequest();
    }

    public static function getInstance(Closure $initialize)
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return $initialize(self::$instance);
    }

    public function hasPost($input)
    {
        return  filter_has_var(INPUT_POST, $input) ?
            true :
            false;
    }

    public function except($key)
    {
        $this->except = $this->security->sanitize($key);
        return $this;
    }

    public function post($key = null, $value = null)
    {
        if (!is_null($this->except)) {
            unset($this->request['post'][$this->except]);
        }

        $postValue = '';

        if ($key !== null &&
            strpos($key, '.') == false &&
            is_null($value) == true
        ) {
            $key = $this->security->sanitize($key);
            $postValue = $this->security->sanitize($this->request['post'][$key]);
            $this->request['post'][$key] = $postValue;

            if (array_key_exists($key, $this->request['post'])) {
                return $this->request['post'][$key];
            }

            throw new InvalidArgumentException("Invalid $key passed to ".__METHOD__);
        }

        if ($key !== null &&
           strpos($key, '.') == true &&
           is_null($value) == true
        ) {
            $expression = explode('.', $key);
            $firstKey = $this->security->sanitize($expression[0]);
            $secondKey = $this->security->sanitize($expression[1]);

            if (isset($expression[2])) {
                throw new InvalidArgumentException('Post doesn\'t allows more than one key');
            }

            $postValue = $this->security->sanitize($this->request['post'][$firstKey][$secondKey]);
            $this->request['post'][$firstKey][$secondKey] = $postValue;

            return $this->request['post'][$firstKey][$secondKey];
        }

        if (is_null($key) ===false && is_null($value) === false) {
            try {
                 // Sets new value for given POST variable.
                 // @param string $key Post variable name
                 // @param mixed $value     New value to be set.
                 //
                $this->request['post'][$key] = $value;
            } catch (InvalidArgumentException $ex) {
                echo $ex->getMessage();
            }

            return true;
        }

        if (is_null($key)) {
            $postArray = $this->security->sanitize($this->request['post']);
            return (!empty($postArray)) ?
                $postArray :
                null;
        }
    }

    public function htmldecode($string)
    {
        return html_entity_decode($string);
    }

    protected function getRequest()
    {
        return array(
            'get'       => $_GET,
            'post'      => $_POST,
            'cookie'    => $_COOKIE
        );
    }

    public function __call($name, $arguments)
    {
        var_dump($name);
        var_dump($arguments);
        call_user_func_array(array($this, $name), $arguments);


    }

    private function setCookie(
        $name,
        $value,
        $expire = 0,
        $path = '/',
        $domain = null,
        $security = false,
        $httpOnly = false
    ) {
        if ($this->cookieInstance instanceof CookieInterface) {
            echo "Cookie Instane";
        }


    }

    private function getCookie($name)
    {

    }

    /**
     * Sets or returns the cookie variable value.
     */
    public function cookie($method = null, $arguments = array())
    {
         $cookie = Cookie::instance(
             $this->request,
             function ($instance) {
                return $instance;
             }
         );

        echo $cookie->get('cygnite_test');

        $cookie->setName('cygnite_cookie')
                    ->setValue('Cygnite Framework Cookie Testing')
                    ->setExpire('+1 Hours')
                    ->setPath('/');
                    //->setDomain('www.cookie.com')
                    //->setSecure(false)
                    //->setHttpOnly(true);
        $cookie->destroy('Cygnite_cookie');

        if ($cookie->save()) {
            echo $cookie->get('cygnite_test').' cookie has been set';
        }
    }
}