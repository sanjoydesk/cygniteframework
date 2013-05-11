<?php

    /*
     *===============================================================================================
     *  An open source application development framework for PHP 5.1.6 or newer
     *
     * @Package
     * @Filename                             : IMemoryStorage.php
     * @Abstract Class                    : IMemoryStorage
     * @Description                         : This class is used to abstract methods of drivers
     * @Author	     	 : Appsntech Dev Team
     * @Copyright	: Copyright (c) 2013 - 2014,
     * @License		: http://www.appsntech.com/license.html
     * @Link		: http://appsntech.com
     * @Since		: Version 1.0
     * @Filesource
     * @Warning                            : Any changes in this library can cause abnormal behaviour of the framework
     * ===============================================================================================
     */
    abstract class IMemoryStorage
    {
        /* Abstract store method of caching*/
         abstract protected function set_data($key,$value);
         /* Abstract the cache retriving function */
         abstract public function get_data($key);
        /* Abstract the cache destroy function */
         abstract public function destroy($key);
    }