<?php
namespace Apps\Models;

use Cygnite\Cygnite;
use Cygnite\Sparker\CF_BaseModel;

class Activerecords extends CF_BaseModel
{
    function __construct()
    {
       parent::__construct('cygnite');
    }

    public function gettags()
    {
         $data= $this->select('all')->fetch_all('registration');
         $this->flushresult();
         return $data;
    }

    public function getdetails()
    {
        $where = array(
                    'Name =' => 'Sanjay',
                    'id >'=> '4'
        );

        return $this->where('name','Sanjay','=')
                             ->group_by(array('name'))
                             ->order_by('id','DESC')
                             ->limit(3)
                             ->fetch_all('guestbook');
    }

     public function insert()
    {
        $this->name = "Cygnite Active Records !!";
        $this->entry_date = date('Y-m-d H:m:s');
        $this->comment = 'Insert query http://www.cygniteframework.com';
        $this->save('guestbook');
    }

    public function update()
    {
        $this->name = "Cygnite Active Records Update Query";
        $this->entry_date = date('Y-m-d H:m:s');
        $this->comment = 'Update query http://www.cygniteframework.com';
        $this->save('guestbook',array('id'=> 27));
    }

    public function delete()
    {
        $this->id = 26;
        $this->remove('guestbook');
    }
}