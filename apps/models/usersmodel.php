<?php
/*
*===============================================================================================
*
* ===============================================================================================
*/
    class UsersmodelAppsModels extends CF_ApplicationModel
    {

                     function __construct()
                    {
                           parent::__construct();
                           //$this->petro_dir->drivers();
                           //$this->petro_dir->properties();
                    }

                    public function getdetails()
                    {
                       $where = array(
                                                    'category_name =' => 'cons',
                                                   'id >'=> '4'
                        );
                     //echo $this->petro_dir->escape('alert("Hi")');
                       /*
                        Condition 1 :  If filed_name is string
                        -----------------------------------------------------------------------------------------------------------------------
                            i.  $this->petro_dir->where('Name','Sanjay');  // If Thrid parameter not passed system will defaultly take as where 'field_name' = 'value'
                           ii. $this->petro_dir->where('Name','Sanjay','='); //Where condition will process based on users third parameter passed on where method

                       Condition 2 :  If filed_name is array
                          i.  If mutiple conditions exists then the array conditions can be passed into where method as below.
                        ------------------------------------------------------------------------------------------------------------------------
                               $where = array(
                                                    'Name =' => 'Sanjay',
                                                   'id >'=> '4'
                               );
                               $this->petro_dir->where($where);
                                [NOTE: ISSUE: If you pass only one condition as array shows unknown result. example:
                                       $where = array(
                                                            'Name =' => 'Sanjay'
                                       );]

                          ii. Not Implemented
                        ---------------------------------------------------------------------------------------------------------------------
                                $where = array(
                                                    'Name' => 'Sanjay',
                                                   'id'=> '4'
                               );
                               $conditions = array('=','>');
                                $this->petro_dir->where($where," ", $conditions);

                       iii. Not Implemented
                       ---------------------------------------------------------------------------------------------------------------------
                                $fields = array("Name","Comment");
                               $values = array('Sanjay','Hi ! This is Framework');
                               $conditions = array('=','LIKE');
                              $this->petro_dir->where($fields,$values, $conditions);

                       */
                        $data =  $this->petro_dir->where('category_name','cons')->order_by('id','DESC')->limit(3)->select('category_name,friendlyurl')->fetch_all('categories'); //,'FETCH_BOTH'
                     /*     $this->petro_dir->order_by('id','DESC');
                         $this->petro_dir->limit(3);
                        $this->petro_dir->where($where);
                         $this->petro_dir->select('Name,Comment');
                         $data = $this->petro_dir->fetch_all('guestbook');
                         */

                        //$data =  $this->petro_dir->query('SELECT Name FROM guestbook');$data->fetchAll();
                        //echo "<br>".$data[0]->Name."<br>";
                     //  var_dump($this->petro_dir->get_error_info());
                       
                       $this->petro_dir->debug_query();
                       $this->petro_dir->flushresult();
                       if($this->petro_dir->num_row_count() > 0)
                           return $data;
                       else
                           return array();
                    }

                    public function insert($insertarray = array())
                    {
                            $this->petro_dir->insert('categories',$insertarray);
                            $this->petro_dir->last_inserted_Id();
                            $this->petro_dir->debug_query();
                            return TRUE;
                    }
                    public function edit($updatearray = array())
                    {
                        $this->petro_dir->where('id',26);
                        $this->petro_dir->update('categories',$updatearray);
                        $this->petro_dir->debug_query();
                    }

                    public function delete($id)
                    {
                        $this->petro_dir->where('id',$id);
                        $this->petro_dir->delete('guestbook');
                        $this->petro_dir->debug_query();
                    }

                    public function getProducts()
                    {
                            $query = "SELECT * from products";
                            $stmt = $this->test->prepare($query);
                            $stmt->execute();
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            return $data;
                    }
    }