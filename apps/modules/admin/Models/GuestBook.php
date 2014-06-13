<?php
namespace Apps\Modules\Admin\Models;

use Cygnite\Application;
use Cygnite\Common\UrlManager\Url;
use Cygnite\Database\Schema;
use Cygnite\Database\ActiveRecord;

class GuestBook extends ActiveRecord
{
    //your database connection name
    protected $database = 'cygnite';

    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

}// End of the ShoppingProducts Model