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
               //$this->petro_dir->drivers();
               //$this->petro_dir->properties();
        }

        public function getuserlist()
        {
           /* return array(
                                    'username'=> 'Sanjay',
                                    'email'=> 'sanjoy09@hotmail.com'
            );  */
          // $this->petro_dir->select('all');
           $data =  $this->cygnite->fetch_all('userdetails'); //,'FETCH_BOTH'
           $this->cygnite->flushresult();
          // $this->petro_dir->debug_query();
           if($this->cygnite->num_row_count() > 0)
                    return $data;
        }
}