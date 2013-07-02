<?php  if ( ! defined('CF_SYSTEM')) exit('No direct script access allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : CF_Encrypt
 * @Description                   : This library used to encrypt and decrypt user input.
 * @Author                           : Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class CF_Encrypt
    {

            private $securekey, $iv;
            var $value;
            /*
             *  Constructor function
             * @param string - encryption key
             *
             */
            function __construct()
            {
                   $encryptkey = Config::getconfig('global_config','cf_encryption_key');
                  $callee = debug_backtrace();
                    if(!is_null($encryptkey)) :
                                $this->set($encryptkey);
                                if(!function_exists('mcrypt_create_iv')):
                                        GHelper::display_errors(E_USER_WARNING, 'Unhandled Exception',"Mcrypt extention library not loaded", $callee[1]['file'],$callee[1]['line'],TRUE);
                                else:
                                $this->iv = mcrypt_create_iv(32);
                                endif;
                    else:
                              GHelper::display_errors(E_USER_WARNING, 'Unhandled Exception',
                                      "Please check for encription key inside config file and autoload helper encrypt key is set or not ",
                                      $callee[1]['file'],$callee[1]['line'],TRUE);
                    endif;
            }


            public function set($encryptkey)
            {
                     $this->securekey = hash('sha256',$encryptkey,TRUE);
           }

           public function get()
            {
                return $this->securekey;
            }

            /*
             *  This function is to encrypt string
             * @access  public
             *  @param string
             * @return encrypted hash
             */
            function encrypt($input)
            {
                    if(!function_exists('mcrypt_create_iv')):
                            $callee = debug_backtrace();
                            GHelper::display_errors(E_USER_WARNING, 'Unhandled Exception',"Mcrypt extention library not loaded", $callee[1]['file'],$callee[1]['line'],TRUE);
                   else:
                          $this->value = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
                    endif;
                     return $this->value;
            }

            /*
             *  This function is to decrypt the encoded string
             * @access  public
             *  @param string
             * @return decrypted string
             */
            function decrypt($input)
            {
                //    $this->value = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
                    return $this->value;
            }

            function __destruct()
            {
                    unset($securekey);
                    unset($iv);
            }

    }