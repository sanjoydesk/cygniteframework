<?php
namespace Cygnite\Libraries;

use Cygnite\Cygnite;
use Cygnite\Inflectors;
use Closure;
/*
<code>
    $validation = Validation::instance(
        $_POST,
        function ($validate) {
            $validate->addRule('username', 'required|min:3|max:5')
                ->addRule('password', 'required|is_int|valid_date')
                ->addRule('phone', 'phone|is_string')
                ->addRule('email', 'valid_email');
				
            return $validate;
        }
    );


    if ($validation->run()) {
        echo 'valid';
    } else {
        show($validation->getErrors());
    }
</code>
*/


class Validation
{
    /**
    * POST or GET
    * @var array
    */
    private $param;

    private $rules = array();

    private $errors= array();

    public $columns = array();

    private $validPhoneNumbers = array(10,11,13,14,91);

    const ERROR = '_error';


    private function __construct(array $var)
    {
        $this->param = $var;
    }


    public static function instance(array $var, Closure $callback)
    {
        return $callback(new Validation($var));
    }


    public function addRule($key, $rule)
    {
        $this->rules[$key] = $rule;

        return $this;
    }

    private function required($key)
    {
        $val = trim($this->param[$key]);

        if (empty($val)) {
            $this->errors[$key.self::ERROR] = ucfirst($key).' is '.str_replace('_', ' ', __FUNCTION__);
            return false;
        }

        return true;
    }

    private function validEmail($key)
    {
        $sanitize_email = filter_var($this->param[$key], FILTER_SANITIZE_EMAIL);

        if (filter_var($sanitize_email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$key.self::ERROR] = ucfirst($key).' is not valid';
            return false;
        }

        return true;
    }


    private function isIp($key)
    {
        if (filter_var($this->param[$key], FILTER_VALIDATE_IP) === false) {
            $this->errors[$key.self::ERROR] =
                ucfirst($key).' is not valid '.lcfirst(str_replace('is', '', __FUNCTION__));
            return false;
        }

        return true;
    }

    private function isInt($key)
    {
        $conCate =  '';
        $columnName =  ucfirst($key).' should be ';

        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = '';
        }

        if (filter_var($this->param[$key], FILTER_VALIDATE_INT) === false) {
            $this->errors[$key.self::ERROR] =
                $conCate.$columnName.strtolower(str_replace('is', '', __FUNCTION__)).'ger.';
            return false;
        }

        return true;
    }

    private function isString($key)
    {
        $conCate =  '';
        $columnName =  ucfirst($key).' should be ';
        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = '';
        }

        if (is_string($this->param[$key])) {
            $this->errors[$key.self::ERROR] = $conCate.$columnName.' valid string';
            return false;
        }
        return true;
    }

    private function min($key, $length)
    {
        $conCate = (isset($this->errors[$key.self::ERROR])) ?
            $this->errors[$key.self::ERROR].' and ' :
            '';

        if (mb_strlen($this->param[$key]) >= $length) {
            return true;
        } else {
            $this->errors[$key.self::ERROR] =
                $conCate.ucfirst($key).' should be '.str_replace('_', ' ', __FUNCTION__).'mum '.$length.' characters.';

            return false;
        }
    }


    private function max($key, $length)
    {
        $conCate =  '';
        $columnName =  ucfirst($key).' should be ';
        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = '';
        }

        if (mb_strlen($this->param[$key]) <= $length) {
            $this->errors[$key.self::ERROR] =
                $conCate.$columnName.__FUNCTION__.'mum '.$length.' characters.';

            return false;
        } else {
            return true;
        }
    }


    private function validUrl($key)
    {
        $sanitize_url = filter_var($this->param[$key], FILTER_SANITIZE_URL);

        $conCate =  '';
        $columnName =  ucfirst($key).' is not a';
        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = '';
        }

        if (filter_var($sanitize_url, FILTER_VALIDATE_URL) === false) {
            $this->errors[$key.self::ERROR] = $conCate.$columnName.' valid url.';
            return false;
        }

        return true;
    }


    public function validDate($key)
    {
        $conCate =  '';
        $columnName =  ucfirst($key).' should be ';
        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = 'should be ';
        }

        if (strtotime($this->param[$key]) !== true) {
            $this->errors[$key.self::ERROR] =
                $conCate.$columnName.'valid date.';
        }

        return true;
    }

    private function phone($key)
    {
        $num = preg_replace('#\d+#', '', $this->param[$key]);

        $conCate =  '';
        $columnName =  ucfirst($key).' number is not ';
        if (isset($this->errors[$key.self::ERROR])) {
            $conCate = str_replace('.', '', $this->errors[$key.self::ERROR]).' and ';
            $columnName = '';
        }

        if (in_array(strlen($num), $this->validPhoneNumbers) == false) {
            $this->errors[$key.self::ERROR] = $conCate.$columnName.'valid.';
        }

        return true;
    }


    public function notEmptyFile($key)
    {
        return empty($_FILES[$key]['name']) !== true;
    }

    private function setErrors($name, $value)
    {
        $this->columns[$name] = '<span style="color:red;">'.$value.' doesn\'t match validation rules </span>';
    }

    public function getErrors($column = null)
    {

        if ($column === null) {
            return implode("<br />", array_values($this->errors));
        }

        return $this->errors[$column.self::ERROR];

    }

    public function run()
    {
        $isValid = true;

        if (empty($this->rules)) {
            return true;
        }

        foreach ($this->rules as $key => $val) {

            $rules = explode('|', $val);

            foreach ($rules as $rule) {

                if (!strstr($rule, 'max') &&
                    !strstr($rule, 'min')
                ) {

                    $method = Cygnite::loader()->inflectors->$this->toCameCase($rule);

                    if (is_callable(array($this, $method)) === false) {
                        throw new \Exception('Undefined method '.__CLASS__.' '.$method.' called.');
                    }
                    //echo $key."<br>";
                    if ($isValid === false) {
                        $this->setErrors($key.self::ERROR, ucfirst($key));
                    }

                    $isValid = $this->$method($key);
                    //$isValid = call_user_func(array($this, $rule[0]), array($key));

                } else {

                    $rule = explode(':', $rule);

                    $method = Cygnite::loader()->inflectors->toCameCase($rule[0]);

                    if (is_callable(array($this, $method)) === false) {
                        throw new \Exception('Undefined method '.__CLASS__.' '.$method.' called.');
                    }

                    if ($isValid === false) {
                        $this->setErrors($key.self::ERROR, ucfirst($key));
                    }

                    //$isValid = call_user_func_array(array($this, $rule[0]), array($key,$rule[1]));
                    $isValid = $this->$method($key, $rule[1]);
                }
            }
        }

        return $isValid;

    }
}
