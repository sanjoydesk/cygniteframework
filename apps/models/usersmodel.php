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
                           //$this->cygnite->drivers();
                           //$this->cygnite->properties();
                    }

                    public function getdetails()
                    {
                       $where = array(
                                                    'Name =' => 'Sanjay',
                                                   'id >'=> '4'
                        );
                     //echo $this->cygnite->escape('alert("Hi")');
                       /*
                        Condition 1 :  If filed_name is string
                        -----------------------------------------------------------------------------------------------------------------------
                            i.  $this->cygnite->where('Name','Sanjay');  // If Thrid parameter not passed system will defaultly take as where 'field_name' = 'value'
                           ii. $this->cygnite->where('Name','Sanjay','='); //Where condition will process based on users third parameter passed on where method

                       Condition 2 :  If filed_name is array
                          i.  If mutiple conditions exists then the array conditions can be passed into where method as below.
                        ------------------------------------------------------------------------------------------------------------------------
                               $where = array(
                                                    'Name =' => 'Sanjay',
                                                   'id >'=> '4'
                               );
                               $this->cygnite->where($where);
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
                                $this->cygnite->where($where," ", $conditions);

                       iii. Not Implemented
                       ---------------------------------------------------------------------------------------------------------------------
                                $fields = array("Name","Comment");
                               $values = array('Sanjay','Hi ! This is Framework');
                               $conditions = array('=','LIKE');
                              $this->cygnite->where($fields,$values, $conditions);

                       */
                        $data =  $this->cygnite->where('Name','Sanjay','=')->order_by('id','DESC')->limit(3)->select('Name,Comment')->fetch_all('guestbook'); //,'FETCH_BOTH'
                     /*     $this->cygnite->order_by('id','DESC');
                         $this->cygnite->limit(3);
                        $this->cygnite->where($where);
                         $this->cygnite->select('Name,Comment');
                         $data = $this->cygnite->fetch_all('guestbook');
                         */

                        //$data =  $this->cygnite->query('SELECT Name FROM guestbook');$data->fetchAll();
                        //echo "<br>".$data[0]->Name."<br>";
                     //  var_dump($this->cygnite->get_error_info());
                      // $this->cygnite->debug_query();
                       $this->cygnite->flushresult();
                       if($this->cygnite->num_row_count() > 0)
                                return $data;
                    }

                    public function insert($insertarray = array())
                    {
                            $this->cygnite->insert('guestbook',$insertarray);
                            $this->cygnite->last_inserted_Id();
                            $this->cygnite->debug_query();
                            return TRUE;
                    }
                    public function edit($updatearray = array())
                    {
                        $this->cygnite->where('id',26);
                        $this->cygnite->update('guestbook',$updatearray);
                        $this->cygnite->debug_query();
                    }

                    public function delete($id)
                    {
                        $this->cygnite->where('id',$id);
                        $this->cygnite->delete('guestbook');
                        $this->cygnite->debug_query();
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
