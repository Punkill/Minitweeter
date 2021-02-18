<?php
namespace tweeterapp\control;
use \mf\router\Router;
use \tweeterapp\auth\TweeterAuthentification;
use tweeterapp\view\TweeterView;
class TweeterAdminController extends \mf\control\AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $vue = new TweeterView(null);
        $vue->render('login');
    }

    public function signup()
    {
        $vue = new TweeterView(null);
        $vue->render('signup');
    }
    public function checkLogin()
    {
        $post = $this->request->post;
        $auth = new TweeterAuthentification();
        $rooter = new Router();
        $auth->loginUser($post["username"],$post["password"]);
        $urlForFollowers = $rooter->urlFor('maison', null);
        header("Location: $urlForFollowers", true, 302);
    }
    public function logout()
    {
        $auth = new \mf\auth\Authentification();
        $auth->logout();
        $rooter = new Router();

        $urlForHome = $rooter->urlFor('maison', null);
        header("Location: $urlForHome", true, 302);
    }

    public function checkSignup()
    {
        $auth = new TweeterAuthentification();
        $vue = new TweeterView(null);
        
        if (isset($this->request->post['username'], $this->request->post['password'],$this->request->post['fullname'])) 
        {
            $auth->createUser($this->request->post['username'], $this->request->post['password'],$this->request->post['fullname']);
            $rooter = new Router();
            $urlForHome = $rooter->urlFor('maison', null);
            header("Location: $urlForHome", true, 302);
        } 
        else
        {
            $vue->render('signup');
        }
    }
}