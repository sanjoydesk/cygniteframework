<?php namespace Cygnite\Helpers;

if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/*
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
 * @Package                         :  Packages
 * @Sub Packages               :   Helper
 * @Filename                       :  FormValidator
 * @Description                   :  This helper is used to validate forms required fields.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class Validator
    {
          /*
            * This function is used to validate required fields of user input
            * @false array
            * @false string
            * @return error html
            */
            public static function validate($required_fields = array(),$isrequired,$submit)
            {
                    $num_of_args = func_num_args();
                                if ($num_of_args >= 2):
                                        if(func_get_arg(1) != 'required')
                                                throw new Exception('Missing second argument required !');
                                endif;
                                if(empty($submit))
                                            throw new Exception("Argument missing ! Please pass submit button name as third argument to validate_fields() fuction.");

                                if($isrequired == 'required'):
                                        $errors = 0;
                                        $errors_arr = array();
                                        foreach ($required_fields as $key=>$val) :

                                                if (isset($_POST[$submit])):
                                                                if(is_array($key)) :
                                                                        $errors_arr[]="<span style='color:#D8000C;'>$val is a required field.</span>";
                                                                        $errors++;
                                                                else :
                                                                        if(empty($_POST[$key])):
                                                                                if($val != 'Submit'):
                                                                                        $errors_arr[]="<span style='color:#D8000C;'>$val is a required field.</span>";
                                                                                        $errors++;
                                                                                endif;
                                                                        endif;
                                                             endif;
                                                endif;
                                            endforeach;

                                         if(count($errors_arr) == 0 && count($errors_arr) ==true):
                                                return true;
                                         endif;

                                        $error =  $images_url = $empty_val = "";
                                        if(!empty($errors_arr) || isset($errors)) :

                                                $error .= "<br><div style='background-color:#FDE9EA;border:1px solid #EE2C2C;color:#D8000C;margin-left:40px;margin-right:40px;'>";
                                                $error .="<table border=0 cellpadding=1 cellspacing=0>\n";

                                                        if (is_array($errors_arr)):
                                                                   foreach ($errors_arr as $key=>$val):
                                                                             $error .="<tr>\n<td><!--<img src=\"$images_url/warning_red.png\" border=0>--></td><td> $val</td>\n</tr>\n";
                                                                             $empty_val .= $val;
                                                                   endforeach;
                                                         else:
                                                                $error .="<tr>\n<td><!--<img src=\"$images_url/warning_red.png\" border=0>--></td><td> $errors_arr</td>\n</tr>\n";
                                                        endif;
                                            $error .="</table>\n";
                                                if(isset($_POST[$submit])):
                                                         if($empty_val == "" && empty($empty_val)):
                                                                      unset($errors);
                                                                      return true;
                                                          endif;
                                                            $error .= "&nbsp;There ";
                                                                            if ($errors > 1)
                                                                                    $error .= "are";
                                                                            else
                                                                                    $error .= " is ";
                                                            $error .= "<font color=red><b>  ".$errors."</b></font> error";
                                                                            if ($errors > 1)
                                                                                    $error .= "s";
                                                                            $error .= " found.<br>";
                                                            $error .= "</div><br>";


                                                            return $error;
                                                endif;
                                        endif;
                                endif;
                                unset($errors);
                                unset($errors_arr);
            }

            public static function isValidEmail($email)
            {
                        $email =  self::trimStr($email);
                        if(empty($email))
                                throw new Exception("Email falseeter missing  !");

                        if(isset($_POST[$email])):
                                $post_email = $_POST[$email];
                                //( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true; codeingiter
                                  if(preg_match("/^[a-zA-Z]w+(.w+)*@w+(.[0-9a-zA-Z]+)*.[a-zA-Z]{2,4}$/", $post_email) === 0)
                                            return false;
                                  else
                                            return true;

                        endif;
            }

            /*
             * This function is used to trim the post values
             * @false sting
             */
            private function trimStr($value)
            {
                $trimed_value = trim($value);
                return $trimed_value;
            }

            public static function isEmail($email = "")
           {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                            return true;
                    else
                           return false;
            }

            public static function isValidIP($ip_address = "" )
            {
                    if (filter_var($ip_address, FILTER_VALIDATE_IP))
                            return true;
                     else
                           return false;
            }

            public static function  getInputRange($intputvalue = "", array $range)
            {
                  /*   range check
                   $int_a = '4';
                    $options = array(
                                                    'range' => array(
                                                    'min_range' => 0,
                                                    'max_range' => 3,
                    )
                    );*/
                    if (filter_var($intputvalue, FILTER_VALIDATE_INT, $range) !== false)
                                echo "This given ".$intputvalue."  is considered valid and between ".$range['range']['min_range']."and ".$range['range']['max_range']."\n";
                     else
                               echo "Given input is considered Invalid and range mismatched";
            }

            public static function isNum($value)
            {
                      if (!isNumeric($value))
                             return false;
                      else
                          return true;
            }

            /*
                 * This function used to check whether the given array is number or not
                 *  @false int
                 * @return boolean true or false
                 */

                function isNumericArray(array $arrayinput)
               {
                    foreach ($arrayinput as $key => $value):
                             if (!isNumeric($value)) return false;
                    endforeach;

                    return true;
                }

                                  /*
                   *  This function is used to check password strength
                   * @false string
                   * @ return boolean
                   *  Password must be at least 6 characters and must contain at least one lower case letter, one upper case letter and one digit
                   */

                  public static function checkPassStrength($password="", $min  ="")
                 {
                         // Password must be strong
                        if(preg_match("/^.*(?=.{{$min},})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/" , $password) === 0)
                                return "Password must be at least 6 characters and must contain at least one lower case letter, one upper case letter and one digit";

                  }


                /**
                * Integer
                *
                * @false	string
                * @return	boolean value
                */
                public static function isInteger($str)
                {
                         return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
                }
    }