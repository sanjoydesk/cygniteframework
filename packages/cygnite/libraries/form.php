<?php
namespace Cygnite\Libraries;

if ( ! defined('CF_SYSTEM')) exit('No External script access allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @package                           :  Packages
 * @subpackages                   :  Library
 * @filename                           :  CF_HTMLForm
 * @description                       :  This library used to generate all html form tags
 * @author                              :  Cygnite Dev Team
 * @copyright                         :  Copyright (c) 2013 - 2014,
 * @link	                       :  http://www.cygniteframework.com
 * @since	                      :  Version 1.0
 * @Filesource
 * @warning                           :   Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

/*************************************************************************************************
 * Example Usage
 *
 * $object = Form::initialize("form");
 * print $object->input("name",array("type"=>"text"))->class("textbox","required")->id("name");
 * print $object->input("age")->type("password")->value("true")->id("age");
 * print $object->textarea("age1")->value("true")->id("age");
 * print $object->select("years")->style("width:100px;")->options(array("1997"=>"1997","1996"=>"1996","1995"=>"1995","1994"=>"1994","1993"=>"1993","1992"=>"1992","1991"=>"1991"))->id("years");
 *
 *
 */

    class CForm
    {

        private static $object_holder = array();

        public static $formname;

        public static $form_open;

        private $elements = array();

        private function __construct(){}

        public static function initialize($form_name)
        {
            self::$formname = $form_name;
            ob_start();
            if(!isset(self::$object_holder[$form_name])):
                    self::$object_holder[$form_name] = new self();
            endif;
                    return self::$object_holder[$form_name];
        }

        public static function open()
        {
            $args = func_get_args();
            $element_str ="";
             if(is_null(self::$form_open)):
                    foreach($args[0] as $key=>$value):
                         $element_str .= "{$key} = '{$value}' ";
                    endforeach;
             endif;

            return "<form name='".self::$formname."' $element_str>".PHP_EOL;
        }

        public static function close()
        {
             if(!is_null(self::$form_open))
                return "</form>";
        }

        public function __call($function_name,$arguments = array())
        {
                $function_name = 'Cygnite\\Libraries\\'.ucfirst($function_name);

                if(isset($this->elements[$arguments[0]]))
                   return $this->elements[$arguments[0]];

                if(class_exists($function_name)):
                        $this->elements[$arguments[0]] = new $function_name;
                        $this->elements[$arguments[0]]->element_name = $arguments[0];
                        if(isset($arguments[1]))
                        $this->elements[$arguments[0]]->attributes = $arguments[1];
                endif;
                return $this->elements[$arguments[0]];
        }

        public function __destruct()
        {
            ob_get_clean();
               ob_end_flush();
             unset($this);
        }
   }

    class CF_Form_Elements
    {
        public $attributes = array();
        public $element_name;
        public $element_value;


        public function __construct(){}


        public function __call($attributes,$arguments)
        {
        $this->attributes[$attributes] = implode(" ",$arguments);
        return $this;
        }

        protected function attributes()
        {
            $element_str = "";
                foreach ($this->attributes as $key=>$value):
                     $element_str .= "{$key} = '{$value}' ";
              endforeach;
        return $element_str;
        }
        public function __toString()
        {
             return "<".$this->element." name='".$this->element_name."' ".$this->attributes()." />";
        }

        public function __destruct()
        {
            unset($this);
        }

     }

    class Input extends CF_Form_Elements
    {
        protected $element = "input";
    }

    class Select extends CF_Form_Elements
    {
        protected $element = "select";
        public $element_name;
        public $element_options = array();

        public function options($element_options = array())
        {
                $this->element_options = $element_options;
                return $this;
        }
        public function __toString()
        {
                 foreach ($this->element_options as $key=>$value):
                        $this->element_value .= "<option value='{$key}'>{$value}</option>";
                 endforeach;
            return "<".$this->element." name='".$this->element_name."' ".$this->attributes().">{$this->element_value}</".$this->element.">";
        }
    }

    class Textarea extends CF_Form_Elements
    {
        protected $element = "textarea";
        public function __toString()
        {
                if(isset($this->attributes['value'])):
                        $this->element_value = $this->attributes['value'];
                        unset($this->attributes['value']);
                endif;
                return "<".$this->element." name='".$this->element_name."' ".$this->attributes().">{$this->element_value}</".$this->element.">";
        }
    }

    class Label extends CF_Form_Elements
    {
        protected $element = "label";
        public function __toString()
        {
            if(isset($this->attributes['value'])):
                     $this->element_value = $this->attributes['value'];
                    unset($this->attributes['value']);
            endif;

            return "<".$this->element." for='".str_replace(' ',' _', strtolower($this->element_value))."' ".$this->attributes().">{$this->element_value}</".$this->element."> &nbsp";
        }
    }
