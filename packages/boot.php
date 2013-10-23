<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
* ------------------------------------------------------
* Define the Cygnite Framework  Version
* ------------------------------------------------------
*/
defined('CF_VERSION') or define(
    'CF_VERSION',
    '(v1.0.5-alpha)'
);

/*----------------------------------------------------
* Define Framework Extension
* ----------------------------------------------------
*/
defined('FRAMEWORK_PREFIX') or define('FRAMEWORK_PREFIX', 'CF_');

/* -------------------------------------------------------------------
*  Define application libraries constant
* -------------------------------------------------------------------
*/
define('APPVENDORSDIR', APPPATH.DS.'vendors');

/*
* -------------------------------------------------------------
* Check minimum version requirement of Cygnite
* and trigger exception is not satisfied
* -------------------------------------------------------------
*/
if (version_compare(PHP_VERSION, '5.3', '<') === true) {
    @set_magic_quotes_runtime(0); // Kill magic quotes
    throw new \ErrorException(
        'Sorry Cygnite Framework will
        run on PHP version  5.3 or greater! \n'
    );
}

function show($resultArray = array(), $hasExit = "")
{
    echo '<pre>';
    print_r($resultArray);
    echo '</pre>';
    if ($hasExit === 'exit') {
        exit;
    }
}

global $router,$events;

$events = new Cygnite\Base\Events();

$events->attach("exception", '\\Cygnite\\onExceptions');

// Create a Router
$router = new \Cygnite\Base\Router;

require_once CF_SYSTEM.DS.'cygnite'.DS.'helpers'.DS.'ghelper'.EXT;
require CF_SYSTEM.DS.'cygnite'.DS.'robotloader'.EXT;
require CF_SYSTEM.DS.'cygnite'.DS.'cygnite'.EXT;