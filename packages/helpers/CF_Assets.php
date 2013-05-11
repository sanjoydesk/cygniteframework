<?php
        /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.1.6 or newer
         *
         * @Package                         :
         * @Filename                       : boot.php
         * @Description                   : This file is used to auto load all base libraries
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         * <link rel="stylesheet" type="text/css" href="http://localhost/phcfgnite/webroot/css/style.css">
         */

            class Assets
            {
                   private $style;
                   private $script;

                  public static function add_style($href = "",$type="text/css")
                  {
                        $style = '<link rel="stylesheet" type="'.$type.'" href="'.GlobalHelper::base_path().$href.'">';
                        return $style;
                  }

                  public function __toString()
                  {
                        
                  }

                  public static function add_script()
                  {

                  }

                  public static function add_ajax()
                  {

                  }


            }