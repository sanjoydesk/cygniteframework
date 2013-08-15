<?php
    error_reporting(-1);
    /*
    * ------------------------------------------------------
    *  Define the Cygnite Framework  Version
    * ------------------------------------------------------
    */
    defined('CF_VERSION') OR define('CF_VERSION', ' <span class="version">(alpha 1.0.2)</span>');

    /*----------------------------------------------------
    * Define Framework Extension
    * ----------------------------------------------------
    */
    defined('FRAMEWORK_PREFIX') OR define('FRAMEWORK_PREFIX','CF_');

    /* -------------------------------------------------------------------
    *  Define application libraries constant
    * -------------------------------------------------------------------
    */
    define('APPVENDORSDIR',APPPATH.DS.'vendors');

    if (!phpversion() <  '5.3')
          @set_magic_quotes_runtime(0); // Kill magic quotes

  /**
    * -------------------------------------------------------------------------------------------------------
    *  Check minimum version requirement of cygnite and trigger exception is not satisfied
    * -------------------------------------------------------------------------------------------------------
    */
    if (version_compare(PHP_VERSION, '5.3', '<') )
        trigger_error('Sorry Cygnite Framework will only run on PHP version  5.3 or greater! \n',E_USER_ERROR);

 require_once CF_SYSTEM.DS.'cygnite'.DS.'helpers'.DS.'ghelper'.EXT;
require CF_SYSTEM.DS.'cygnite'.DS.'robotloader'.EXT;
require CF_SYSTEM.DS.'cygnite'.DS.'cygnite'.EXT;
//require_once CF_SYSTEM.DS.'cygnite'.DS.'helpers'.DS.'url'.EXT;
//require_once CF_SYSTEM.DS.'cygnite'.DS.'helpers'.DS.'config'.EXT;
