<?php
namespace Apps\Controllers;

use Cygnite\Foundation\Application;
use Cygnite\Mvc\Controller\AbstractBaseController;
use Cygnite\Common\Validator;
use Apps\Components\Form\Registration;
use Cygnite\Common\CookieManager\Cookie;

class HomeController extends AbstractBaseController
{
    /**
    * --------------------------------------------------------------------------
    * The Default Controller
    *--------------------------------------------------------------------------
    *  This controller respond to uri beginning with home and also
    *  respond to root url like "home/index"
    *
    * Your GET request of "home/index" will respond like below -
    *
    *      public function indexAction()
    *     {
    *            echo "Cygnite : Hellow ! World ";
    *     }
    * Note: By default cygnite doesn't allow you to pass query string in url, which
    * consider as bad url format.
    *
    * You can also pass parameters into the function as below-
    * Your request to  "home/form/2134" will pass to
    *
    *      public function formAction($id = ")
    *      {
    *             echo "Cygnite : Your user Id is $id";
    *      }
    * In case if you are not able to access parameters passed into method
    * directly as above, you can also get the uri segment
    *  echo Url::segment(3);
    *
    * That's it you are ready to start your awesome application with Cygnite framework.
    *
    */
    protected $layout = 'layout.users';

   // protected $templateEngine = false;

   // protected $templateExtension = '.html.twig';

   //protected $autoReload = true;

   public $service;

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
                'messege' => 'Welcome to Cygnite framework',
            )
        );
   }

   public function testAction()
   {
            //setcookie('City', "Edmonton43003", (time()+3600), "/");			
			 $session = $this->get('cygnite.base.router');
			//show($this->get());
			$registration = new Registration();
			echo $registration->buildForm()->render();
			// show($session);

             $session = $this->get('cygnite.common.session-manager.session');
             $session->save('name', 'Sanjoy Dey');
             echo $session->get('name');

             $cookie = Cookie::create(function ($cookie) {

                    $cookie->setName('cygnite')
                                   ->setValue('Cygnite Framework Cookie')
                                   ->setExpire((time()+3600))
                                   ->setPath('/')
                                   ->save();

                    return $cookie;
                });

            //$cookie = Cookie::create();
           //$cookie->destroy('cygniteCookie');

            //var_dump($cookie->get('cygniteCookie'));

            echo $cookie->get('cygnite');


        $response = $this->call('modules.admin.controllers.user@index', array('id' => '2333'));

       $this->render('application', array(
                'author' => $this->author,
                'email' => 'sanjoy09@hotmail.com',
                'messege' => 'Welcome to Cygnite framework',
                'guestwidget' => $response
            ));
   }

   public function testingAction()
   {
       echo "Hello Testing ";
   }

}//End of your home controller