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
                           //$this->cygnite->db->drivers();
                           //$this->cygnite->db->properties();
                    }

                    public function getdetails()
                    {

                     //echo $this->cygnite->db->escape('alert("Hi")');
                       /*
                        Condition 1 :  If filed_name is string
                        -----------------------------------------------------------------------------------------------------------------------
                            i.  $this->cygnite->db->where('Name','Sanjay');  // If Thrid parameter not passed system will defaultly take as where 'field_name' = 'value'
                           ii. $this->cygnite->db->where('Name','Sanjay','='); //Where condition will process based on users third parameter passed on where method

                       Condition 2 :  If filed_name is array
                          i.  If mutiple conditions exists then the array conditions can be passed into where method as below.
                        ------------------------------------------------------------------------------------------------------------------------
                               $where = array(
                                                    'Name =' => 'Sanjay',
                                                   'id >'=> '4'
                               );
                               $this->cygnite->db->where($where);
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
                                $this->cygnite->db->where($where," ", $conditions);

                       iii. Not Implemented
                       ---------------------------------------------------------------------------------------------------------------------
                                $fields = array("Name","Comment");
                               $values = array('Sanjay','Hi ! This is Framework');
                               $conditions = array('=','LIKE');
                              $this->cygnite->db->where($fields,$values, $conditions);

                            $this->cygnite->db->group_by(array('category_name','friendlyurl'));
                            $this->cygnite->db->group_by('category_name')
                       */
                        $where = array(
                                                    'Name =' => 'Sanjay',
                                                   'id >'=> '4'
                        );
                        $this->cygnite->sql_generator('dbfetch',TRUE);
                        $data =  $this->cygnite->db->where('name','Sanjay','=')
                                                                          ->group_by(array('name','comment'))
                                                                          ->order_by('id','DESC')
                                                                          ->limit(3)->select('name,comment')
                                                                          ->fetch_all('guestbook'); //,'FETCH_BOTH'
                     /*     $this->cygnite->db->order_by('id','DESC');
                         $this->cygnite->db->limit(3);
                        $this->cygnite->db->where($where);
                         $this->cygnite->db->select('Name,Comment');
                         $data = $this->cygnite->db->fetch_all('guestbook');
                         */

                        //$data =  $this->cygnite->db->query('SELECT Name FROM guestbook');$data->fetchAll();
                        //echo "<br>".$data[0]->Name."<br>";
                     //  var_dump($this->cygnite->db->get_error_info());
                      //$this->cygnite->db->debug_query();
                       $this->cygnite->db->flushresult();
                       if($this->cygnite->db->num_row_count() > 0)
                                return $data;
                    }

                    public function getusers()
                    {
                          $data =  $this->hris->query("SELECT * FROM hs_hr_employee")->fetchAll(PDO::FETCH_ASSOC);
                          //var_dump($data);
                          return $data;
                    }

                    public function query()
                    {
                         $this->cygnite->sql_generator('dbfetch',TRUE);
                         $query = $this->cygnite->db->prepare_query("SELECT name,comment FROM `guestbook` WHERE `name` = 'Sanjay' GROUP BY `name`,`comment` ORDER BY id DESC");
                         $data = $query->fetchAll(PDO::FETCH_CLASS);
                         return $data;
                    }

                    public function insert($insertarray = array())
                    {
                            $data = array(
                                                     'name' => $insertarray['name_of_author'],
                                                    'mobile_no' => $insertarray['mobile_no'],
                                                    'email' => $insertarray['email_id'],
                                                    'address1' => $insertarray['address_line1'],
                                                    'address2' => $insertarray['address_line2'],
                                                    'city' => $insertarray['city'],
                                                    'district' => $insertarray['district'],
                                                    'state' => $insertarray['state'],
                                                    'country' => $insertarray['country'],
                                                    'zip_code' => $insertarray['zipcode']
                            );
                            $this->cygnite->sql_generator('dbfetch',FALSE);
                            $this->cygnite->db->insert('registration',$data);
                        //    $this->cygnite->db->last_inserted_Id();
                          $this->cygnite->db->debug_query();

                            return TRUE;
                    }
                    public function edit($updatearray = array())
                    {
                          $this->cygnite->sql_generator('dbfetch',FALSE);
                        $this->cygnite->db->where('id',26);
                        $this->cygnite->db->update('guestbook',$updatearray);
                        $this->cygnite->db->debug_query();
                    }

                    public function delete($id)
                    {
                          $this->cygnite->sql_generator('dbfetch',FALSE);
                        $this->cygnite->db->where('id',$id);
                        $this->cygnite->db->delete('guestbook');
                        $this->cygnite->db->debug_query();
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
