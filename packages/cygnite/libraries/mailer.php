<?php
namespace Cygnite\Libraries;

use Cygnite\Vendors\Phpmailer\Email;

if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3  or newer
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
 * @Sub Packages                    :  Library
 * @Filename                        :  CF_Pdf
 * @Description                     : This library will be available with all features in next version.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                       :  Copyright (c) 2013 - 2014,
 * @Link	                    :  http://www.cygniteframework.com
 * @Since	                    :  Version 1.0
 * @Filesource                      :
 * @Warning                         :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */


/*
 include CF_Mailer.php;

$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'jswan';                            // SMTP username
$mail->Password = 'secret';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'from@example.com';
$mail->FromName = 'Mailer';
 *
$mail->AddAddress('josh@example.net', 'Josh Adams');  // Add a recipient
$mail->AddAddress('ellen@example.com');               // Name is optional
$mail->AddReplyTo('info@example.com', 'Information');
$mail->AddCC('cc@example.com');
$mail->AddBCC('bcc@example.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->AddAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->AddAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->IsHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';


*/
class Mailer extends Email
{
    public function __construct()
    {
          parent::__construct();
    }

}
