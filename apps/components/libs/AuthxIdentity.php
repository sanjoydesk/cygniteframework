<?php
namespace Apps\Components\Libs;

use Cygnite\Cygnite;

class Authxidentity
{
    public $userCredentials = array();

    public function __construct($credentials = array())
    {
           $this->userCredentials = $credentials;
    }

    public function getDbName()
    {
        return 'cygnite';
    }

    public function getTableName()
    {
        return 'userdetails';
    }

    public function getSessionConfig()
    {
         return array(
                              'key' => 'validated_user',
                              'value' => array('id','username','email')
                                );
    }
}