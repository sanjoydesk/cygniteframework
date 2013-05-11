<?php

/*
*---------------------------------------------------------------------------------
* AUTO-LOADER
* --------------------------------------------------------------------------------
* This file is used to specify which files should be loaded by default.
*
*
* Created Date   : 05-07-2012
* Modified Date  : 06-07-2012
*/

$CF_CONFIG['autoload'] = array(
                                                        'helpers' => array('encrypt'), /* Autoload Helper Files */
                                                        'libraries' => array('authentication'), /* Autoload Library Files*/
                                                        'plugins' => array(), /* Autoload Library Files*/
                                                        'model'    => array()   /* Autoload Model Files*/
);