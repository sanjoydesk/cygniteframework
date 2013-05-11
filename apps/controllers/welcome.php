<?php if ( ! defined('CF_BASEPATH')) exit('No direct script access allowed');
/*
*===============================================================================================
*
* ===============================================================================================
*/
class WelcomeAppsController extends CF_ApplicationController
{
        function __construct()
        {
            parent::__construct();
            $this->app()->model('DBmodel');

        }

        public function action_index()
        {
            $data['userdetails']=  $this->app()->DBmodel->getdetails();
            $data['values'] = "Sanjay";
            $this->app()->render("user_details_view",$data);
        }
        public function action_userlist()
        {
            echo "This is userlist";
        }
        public function action_testing()
        {
                echo "I am in ".__METHOD__;

        }

}