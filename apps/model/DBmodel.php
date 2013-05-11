<?php
 class DBmodelAppsModel extends cf_ApplicationModel
{

                 function __construct() {
                       parent::__construct();
                       //$this->phpignite->drivers();
                       //$this->phpignite->properties();
                }

                function test()
                {
                    echo "here I am ";
                }

                function getdetails()
                {
                   $where = array(
                                                'Name =' => 'Sanjay',
                                               'id >'=> '4'
                    );
                 //echo $this->phpignite->escape('alert("Hi")');
                   /*
                    Condition 1 :  If filed_name is string
                    -----------------------------------------------------------------------------------------------------------------------
                        i.  $this->phpignite->where('Name','Sanjay');  // If Thrid parameter not passed system will defaultly take as where 'field_name' = 'value'
                       ii. $this->phpignite->where('Name','Sanjay','='); //Where condition will process based on users third parameter passed on where method

                   Condition 2 :  If filed_name is array
                      i.  If mutiple conditions exists then the array conditions can be passed into where method as below.
                    ------------------------------------------------------------------------------------------------------------------------
                           $where = array(
                                                'Name =' => 'Sanjay',
                                               'id >'=> '4'
                           );
                           $this->phpignite->where($where);
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
                            $this->phpignite->where($where," ", $conditions);

                   iii. Not Implemented
                   ---------------------------------------------------------------------------------------------------------------------
                            $fields = array("Name","Comment");
                           $values = array('Sanjay','Hi ! This is Framework');
                           $conditions = array('=','LIKE');
                          $this->phpignite->where($fields,$values, $conditions);

                   */
                    $data =  $this->phpignite->where('Name','Sanjay','=')->order_by('id','DESC')->limit(3)->select('Name,Comment')->fetch_all('guestbook'); //,'FETCH_BOTH'
                 /*     $this->phpignite->order_by('id','DESC');
                     $this->phpignite->limit(3);
                    $this->phpignite->where($where);
                     $this->phpignite->select('Name,Comment');
                     $data = $this->phpignite->fetch_all('guestbook');
                     */

                    //$data =  $this->phpignite->query('SELECT Name FROM guestbook');$data->fetchAll();
                    //echo "<br>".$data[0]->Name."<br>";
                 //  var_dump($this->phpignite->get_error_info());
                  // $this->phpignite->debug_query();
                   $this->phpignite->flushresult();
                   if($this->phpignite->num_row_count() > 0)
                            return $data;
                }

                function insert($insertarray = array())
                {
                        $this->phpignite->insert('guestbook',$insertarray);
                        $this->phpignite->last_inserted_Id();
                        $this->phpignite->debug_query();
                        return TRUE;
                }
                function edit($updatearray = array())
                {
                        $this->phpignite->where('id',26);
                        $this->phpignite->update('guestbook',$updatearray);
                        $this->phpignite->debug_query();
                }

                function delete($id)
                {
                    $this->phpignite->where('id',$id);
                    $this->phpignite->delete('guestbook');
                    $this->phpignite->debug_query();

                }

                function getProducts()
                {
                        $query = "SELECT * from products";
                        $stmt = $this->test->prepare($query);
                        $stmt->execute();
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        return $data;
                }
}

