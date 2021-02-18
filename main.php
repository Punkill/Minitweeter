<?php
session_start();
/* pour le chargement automatique des classes d'Eloquent (dans le répertoire vendor) */
require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$loader = new \mf\utils\ClassLoader('src');
$loader->register();
use \tweeterapp\model\Follow as Follow; 
use \tweeterapp\model\User as User;
use \tweeterapp\model\Like as Like;
use \tweeterapp\model\Tweet as Tweet;
use \tweeterapp\view\TweeterView as Vue;
Vue::addStyleSheet('css/style.css');
$config = parse_ini_file('conf/config.ini');
/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();           /* établir la connexion */

/*$user = User::where('id', '=', 1)->first();
$liste_tweet = $user->tweets()->get();
foreach($liste_tweet as $v)
   echo $v."<br>";*/
   
//Affchage tweet avec  control
/*$ctrl = new tweeterapp\control\TweeterController();
echo $ctrl->viewHome();*/

/* configuration d'Eloquent (cf partie 1 du projet ) */

$router = new \mf\router\Router();
$router->addRoute('maison',
                  '/home/',
                  '\tweeterapp\control\TweeterController',
                  'viewHome',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->setDefaultRoute('/home/');


//http://localhost/kremerthomas/tweeter/main.php/view/?id=49
$router->addRoute('view',
                  '/view/',
                  '\tweeterapp\control\TweeterController',
                  'viewTweet',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
//http://localhost/kremerthomas/tweeter/main.php/user/?id=1
$router->addRoute('user',
                  '/user/',
                  '\tweeterapp\control\TweeterController',
                  'viewUserTweets',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('login',
                  '/login/',
                  '\tweeterapp\control\TweeterAdminController',
                  'login',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checkLogin',
                  '/check_login/',
                  '\tweeterapp\control\TweeterAdminController',
                  'checkLogin',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('signup',
                  '/signup/',
                  '\tweeterapp\control\TweeterAdminController',
                  'signup',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('logout',
                  '/logout/',
                  '\tweeterapp\control\TweeterAdminController',
                  'logout',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('checksignup',
                  '/check_signup/',
                  '\tweeterapp\control\TweeterAdminController',
                  'checkSignup',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('post',
                  '/post/',
                  '\tweeterapp\control\TweeterController',
                  'viewPost',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('send',
                  '/send/',
                  '\tweeterapp\control\TweeterController',
                  'Send',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('Followers',
                  '/followers/',
                  '\tweeterapp\control\TweeterController',
                  'viewFollowers',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('Following',
                  '/following/',
                  '\tweeterapp\control\TweeterController',
                  'viewFollowing',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('Userfollowing',
                  '/Userfollowing/',
                  '\tweeterapp\control\TweeterController',
                  'viewUserfollow',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->run();


/*print_r(\mf\router\Router::$routes);
echo "<br>";
print_r(\mf\router\Router::$aliases);*/