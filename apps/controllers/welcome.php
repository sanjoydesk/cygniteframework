<?php
namespace Apps\Controllers;

use Cygnite\Cygnite;
use Cygnite\BaseController;
use Apps\Components\Authx\Authentication;
use Apps\Components\Authx\Identity;
use Apps\Components\Thumbnail\Image;

class Welcome extends BaseController
{
    /**
    * --------------------------------------------------------------------------
    * The Default Controller
    *--------------------------------------------------------------------------
    *  This controller respond to uri beginning with welcome and also
    *  respond to root url like "welcome/index"
    *
    * Your GET request of "welcome/form" will respond like below -
    *
    *      public function action_form()
    *     {
    *         echo "Cygnite : Hello ! World ";
    *     }
    * Note: By default cygnite doesn't allow you to pass query string in url, which
    * consider as bad url format.
    *
    * You can also pass parameters into the function as below-
    * Your request to  "welcome/form/2134" will pass to
    *
    *      public function action_form($id = ")
    *      {
    *         echo "Cygnite : Your user Id is $id";
    *      }
    * In case if you are not able to access parameters passed into method
    * directly as above, you can also get the uri segment
    *  echo Url::segment(2);
    *
    * That's it you are ready to start your awesome application with Cygnite Framework.
    *
    */
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        /*
        $model = new \Apps\Models\Guestbook();
        $model->name =    "Cygnite Framework Active Records !!";
        $model->entry_date = date('Y-m-d H:m:s');
        $model->comment =  'ORM type insert';
        $model->save();
        */
        /*
        $path = 'apps.png';
        $thumb = new \Apps\Components\Thumbnail\Image();
        $thumb->directory = $path;
        $thumb->fixedWidth  = 100;
        $thumb->fixedHeight = 100;
        $thumb->thumbPath = 'webroot/';
        $thumb->thumbName = 'test';
        $thumb->resize();
        */

        $authConfig = new Identity(
            array (
              'username' => 'sanjay',//post values need to be passed for username or email address field
              'password' =>  'sanjay2123', // your password
              //'status' => 1 // optional field to check user authentication
            )
        );

        $auth = new Authentication($authConfig);

        if ($auth->identifyUser() == false) {
            echo "User Authenticated Successfully";
        } else {
            throw new \Exception("Not a valid User");
        }

        $this->createSections(
            array(
               'header'=>'layout:header.view',
               'content'=>'welcome:register.view',
               'footer'=>'layout:footer.view'
            )
        );

        $this->setLayout(
            'layout:admin',
            array(
                'title'=> "Welcome Sanjoy",
                'header_title'=> "Header Page",
                'content_title'=> "Content Page",
                'footer_title'=> "Footer Page"
            )
        );

    }
}