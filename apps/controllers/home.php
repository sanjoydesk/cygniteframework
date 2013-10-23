<?php
namespace Apps\Controllers;

use Cygnite\Cygnite;
use Cygnite\BaseController;

class Home extends BaseController
{
    /**
    * --------------------------------------------------------------------------
    * The Default Controller
    *--------------------------------------------------------------------------
    *  This controller respond to uri beginning with welcomeuser and also
    *  respond to root url like "home/index"
    *
    * Your GET request of "home/form" will respond like below -
    *
    *      public function action_form()
    *     {
    *            echo "Cygnite : Hellow ! World ";
    *     }
    * Note: By default cygnite doesn't allow you to pass query string in url, which
    * consider as bad url format.
    *
    * You can also pass parameters into the function as below-
    * Your request to  "home/form/2134" will pass to
    *
    *      public function action_form($id = ")
    *      {
    *             echo "Cygnite : Your user Id is $id";
    *      }
    * In case if you are not able to access parameters passed into method
    * directly as above, you can also get the uri segment
    *  echo Url::segment(3);
    *
    * That's it you are ready to start your awesome application with Cygnite Framework.
    *
    */

    private $author = 'Sanjoy Dey';

    private $country = 'India';

     /*
     * Your constructor.
     * @access public
     *
     */
    public function __construct()
    {
          parent::__construct();
    }

    /**
     * Default method for your controller. Render welcome page to user.
     * @access public
     *
     */
    public function indexAction()
    {
        $this->render('welcome')->with(
            array(
                'author' => $this->author,
                'email' => 'sanjoy09@hotmail.com',
                'messege' => 'Welcome to Cygnite Framework',
                'country' => $this->country
            )
        );

    }

    public function testingAction()
    {
        echo "This test ";
        echo \Cygnite\Helpers\Url::segment(2);
    }

    public function usersAction($param, $param1, $param3)
    {
        //$data = Cygnite::loader()->guestbook->fetch();
        //Cygnite::loader()->guestbook->update();
        //Cygnite::loader()->guestbook->delete();
        //Cygnite::loader()->guestbook->generateSchema();
        //show($data);
    }
}//End of your home controller
