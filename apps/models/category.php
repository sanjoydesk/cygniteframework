<?php
/*
*===============================================================================================
*
* ===============================================================================================
*/
 class CategoryAppsModels extends CF_ApplicationModel
{
         function __construct()
        {
               parent::__construct();
               //$this->cygnite->drivers();
               //$this->cygnite->properties();
        }

        public function getuserlist()
        {
           /* return array(
                                    'username'=> 'Sanjay',
                                    'email'=> 'sanjoy09@hotmail.com'
            );  */
          // $this->cygnite->select('all');
           $data =  $this->cygnite->fetch_all('userdetails'); //,'FETCH_BOTH'
           $this->cygnite->flushresult();
          // $this->cygnite->debug_query();
           if($this->cygnite->num_row_count() > 0)
                    return $data;
        }
}