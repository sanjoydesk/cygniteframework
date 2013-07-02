<?php

       /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */

    class PDOConnector extends PDO
    {
        public function connect_db($dns,$username,$password,$persist)
        {
             try {
                      parent::__construct($dns, $username, $password,$persist);
             }catch(Exception $exception){
                    echo $exception->getMessage();
             }
              return $this;
        }
    }