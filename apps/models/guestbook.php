<?php
namespace Apps\Models;

use Cygnite\Cygnite;
use Cygnite\Database\ActiveRecords;
use Cygnite\Database\Schema;

class GuestBook extends ActiveRecords
{

    protected $database = 'cygnite';

    protected $tableName = 'guestbook';

    protected $primaryKey = 'id';

    protected $columns = array(
                            'category_name'=> 'Category Name',
                            //''=> '',
                            //''=> '',
                           // ''=> '',
    );
    
    protected $rules = array(
                            'category_name'
        
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch()
    {
        // Where in with AND conditions
         $whereIn = array(
                      'name IN' => '#"Cygnite","orm","Sanjay"',
                      //'friendlyurl LIKE' => '%Auto%',
            
        );
        
        //WHERE BETWEEN DATE
        $whereBetween = array(
                      'entry_date BETWEEN' => '2012-04-23 05:00:00',
                       date('Y-m-d H:i:s'),
            
        );
        
        //Where with AND conditions
        $whereAND = array(
                      'entry_date LIKE' => '%2012-04-23 05:00:00%',
                      'name=' => 'Sanjay'
            
        );
        
        $where = array(
                      'entry_date LIKE' => '%2013-08-24 05:00:00%',
                      'comment LIKE' => '%ORM%'
            
        );
        
        $whereDate = array(
                      'entry_date >=' => '2013-08-24 05:00:00',
                       'entry_date <=' => date('Y-m-d'),
            
        );
        

        //$this->tableName = 'category_industry';
        
        return $this->select('*')->where($whereAND)
                              //->groupBy(array('category_name'))
                             ->orderBy('id', 'DESC')
                             ->limit(1000)
                             ->findAll('JSON');
    }

    public function insert()
    {
        $this->name = "Cygnite Latest Active Records Test";
        $this->entry_date = date('Y-m-d H:m:s');
        $this->comment = 'Insert query for Cygnite Active Records http://www.cygniteframework.com';
        $this->save();
    }

    public function update()
    {
        $this->name = "Cygnite Latest Active Records Update Query";
        $this->entry_date = date('Y-m-d H:m:s');
        $this->comment = 'Update query for Cygnite Active Records http://www.cygniteframework.com';
        $this->save(66);
    }

    public function delete()
    {
        $this->trash(65);
    }

    public function getDetails()
    {
         return $this->query("Select * from request_training")->findAll();
    }

    public function generateSchema()
    {
        Schema::getInstance(
            $this,
            function ($table) {
                $table->tableName = 'users';
                //$table->drop()->run();

                $table->create(
                    array(
                        array('name'=> 'id', 'type' => 'int', 'length' => 11,
                                       'increment' => true, 'key' => 'primary'),
                        array('name'=> 'username', 'type' => 'string', 'length' =>100),
                        array('name'=> 'password', 'type' => 'string', 'length'  =>16),
                        array('name'=> 'country', 'type' => 'string', 'length'  =>20),
                        array('name'=> 'city', 'type' => 'string', 'length'  =>16, 'null'=> true),
                        array('name'=> 'is_admin', 'type' => 'enum', 'length'  =>array('yes', 'no')),
                        array('name'=> 'price', 'type' => 'decimal', 'length'  =>'10,2'),
                        array('name'=> 'depth', 'type' => 'float', 'length'  =>'10,2'),
                        array(
                            'name'=> 'created_date',
                            'type' => 'datetime',
                            'length'  =>"DEFAULT '0000-00-00 00:00:00'"
                        ),
                        array('name'=> 'rise', 'type' => 'time'),
                        array('name'=> 'time', 'type' => 'timestamp',
                            'length'=> "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
                        ),
                    ),
                    'InnoDB',
                    'latin1'
                )->run();

                /*
                $table->addColumn(
                    'testing',
                    'varchar(100) NOT NULL'
                )->run();


                $table->addColumn(
                    'testing_after_a',
                    'varchar(100) NOT NULL'
                )->after('testing')->run();

                //$table->dropColumn('testing')->run();

                /*  $table->dropColumn(array(
                          'testing',
                          'user_details',
                          'status'
                      )
                  )->run();
                  */
                //$table->addPrimary('testing')->run();

               /*
                $table->addColumn(
                    array(
                        'user_details' => 'varchar(155)',
                        'status' => 'enum("0","1","2")',
                    )
                )->run();
                $table->tableName = 'test';

                /*
                 *
                 $table->addPrimaryKey(
                    array(
                        'id', 'some'
                    )
                )->run();


                var_dump($table->hasColumn('user_details')->run());

                //$table->unique('sunrise')->run();
                $table->unique(array('depth','rise'), 'users')->run();
                //$table->dropUnique('users')->run();
                $table->index('is_admin')->run();
                //$table->dropIndex('is_admin')->run();
                */

                if ($table->hasTable()->run()) {
                    echo "Table exists";
                }
                //$table->rename('users_test');
                /*if($table->drop('users')->run())
                    echo "Table dropped";
                */
            }
        );
    }

}

