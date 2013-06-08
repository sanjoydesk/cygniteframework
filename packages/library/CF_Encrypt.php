<?php  if ( ! defined('CF_SYSTEM')) exit('No direct script access allowed');
/*
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
            function __construct($encrypt_key="")
            {
                    if(!empty($encrypt_key)) :
                                $this->set($encrypt_key);
                                $this->iv = mcrypt_create_iv(32);
                    else:
                                 trigger_error("Please check for encription key inside config file and autoload helper encrypt key is set or not ", E_USER_ERROR);
                    endif;
            }


            public function set()
            {
                     $this->securekey = hash('sha256',$encrypt_key,TRUE);
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
                   $this->value = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
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
                    $this->value = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
                    return $this->value;
            }

            function __destruct()
            {
                    unset($securekey);
                    unset($iv);
            }

    }