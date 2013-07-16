<?php
 class Category extends CF_BaseModel
{
         function __construct()
        {
               parent::__construct();
               //$this->petro_dir->drivers();
               //$this->petro_dir->properties();
        }
        function test()
        {
            echo "Autoloaded model";
        }

        public function getuserlist()
        {
           /* return array(
                                    'username'=> 'Sanjay',
                                    'email'=> 'sanjoy09@hotmail.com'
            );  */
          // $this->petro_dir->select('all');
           $data =  $this->cygnite->db->fetch_all('userdetails'); //,'FETCH_BOTH'
           $this->cygnite->db->flushresult();
          // $this->petro_dir->debug_query();
           if($this->cygnite->db->num_row_count() > 0)
                    return $data;
        }
}