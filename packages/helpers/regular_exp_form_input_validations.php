<?php
/*
Form Validation using Regular expressions
website : http://www.phpjabbers.com/php-validation-and-verification-php27.html
*/
// Full Name must contain letters, dashes and spaces only and must start with upper case letter.

if(preg_match("/^[A-Z][a-zA-Z -]+$/", $_POST["name"]) === 0)
$errName = '<p class="errText">Name must be from letters, dashes, spaces and must not start with dash</p>';

// Address must be word characters only

if(preg_match("/^[a-zA-Z0-9 _-.,:"']+$/", $_POST["address"]) === 0)
$errAddress = '<p class="errText">Address must be only letters, numbers or one of the following _ -. , :" '</p>'";


// Email mask
if(preg_match("/^[a-zA-Z]w+(.w+)*@w+(.[0-9a-zA-Z]+)*.[a-zA-Z]{2,4}$/", $_POST["email"]) === 0)
$errEmail = '<p class="errText">Email must comply with this mask: chars(.chars)@chars(.chars).chars(2-4)</p>';


// Passport must be only digits
if(preg_match("/^d{10}$|^d{12}$/", $_POST["passport"]) === 0) 
$errPassport = '<p class="errText">Passport must be 10 or 12 digits</p>';


// Phone mask             1-800-999-9999      
if(preg_match("/^d{1}-d{3}-d{3}-d{4}$/", $_POST["phone"]) === 0)
$errPhone = '<p class="errText">Phone must comply with this mask: 1-333-333-4444</p>';
// Zip must be 4 digits
if(preg_match("/^d{4}$/", $_POST["zip"]) === 0)
$errZip = '<p class="errText">Zip must be 4 digits</p>';


// Date mask YYYY-MM-DD
if(preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $_POST["date"]) === 0)
$errDate = '<p class="errText">Date must comply with this mask: YYYY-MM-DD</p>';


// User must be digits and letters
if(preg_match("/^[0-9a-zA-Z_]{5,}$/", $_POST["user"]) === 0)
$errUser = '<p class="errText">User must be bigger that 5 chars and contain only digits, letters and underscore</p>';


// Password must be strong
if(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST["pass"]) === 0)
$errPass = '<p class="errText">Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit</p>';
}

/*Form Validation Ends*/
