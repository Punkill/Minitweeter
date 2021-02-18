<?php

namespace tweeterapp\view;

class TweeterView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct( $data ){
        parent::__construct($data);
    }

    /* Méthode renderHeader
     *
     *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
     */ 
    private function renderHeader(){
        return "<h1>MiniTweeTR</h1>";
        
    }

    private function renderTopMenu()
    {
        $auth = new \tweeterapp\auth\TweeterAuthentification();
        $router = new \mf\router\Router();
        $app_root =  (new \mf\utils\HttpRequest())->root;
        $urlHome = $router->urlFor('maison',null);
        if($auth->logged_in)
        {
            $urlFollowees = $router->urlFor('Followers',null);
            $urlLogout = $router->urlFor('logout',null);
            $TopMenu = <<<EOT
            <a href="${urlHome}" id="followees">
                <img src="$app_root/img/home.png">
             </a>
            <a href="${urlFollowees}">
                <img src="$app_root/img/followees.png">
            </a>
            <a href="${urlLogout}">
                <img src="$app_root/img/logout.png">
            </a>
EOT;
        }
        else
        {
            $urlLogin = $router->urlFor('login',null);
            $urlSignup = $router->urlFor('signup',null);
            $TopMenu = <<<EOT
            <a href="${urlHome}">
                <img src="$app_root/img/home.png">
            </a>
            <a href="${urlLogin}">
                <img src="$app_root/img/login.png">
            </a>
            <a href="${urlSignup}">
                <img src="$app_root/img/signup.png">
            </a>
EOT;
        }
        return $TopMenu;
    }
    /* Méthode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter(){
        return 'La super app créée en Licence Pro &copy;2019';
    }

    /* Méthode renderHome
     *
     * Vue de la fonctionalité afficher tous les Tweets. 
     *  
     */
    private function renderHome()
    {

        /*
         * Retourne le fragment HTML qui affiche tous les Tweets. 
         *  
         * L'attribut $this->data contient un tableau d'objets tweet.
         * 
         */
        $r = new \mf\router\Router();
        $chaines ="<div>";
        foreach($this->data as $v)
        {
            $hrefTweet = $r->urlFor('view',array(['id',$v->id]));
            $hrefUser = $r->urlFor('user',array(['id',$v->author]));
            $chaines .="<div class=\"tweet\"><a href=\"$hrefTweet\">".$v->text.'</a><br>'.$v->created_at." <a href=\"$hrefUser\">".$v->author()->first()->fullname."</a></div>";
        }
        return $chaines."</div>";
        
    }
  
    /* Méthode renderUeserTweets
     *
     * Vue de la fonctionalité afficher tout les Tweets d'un utilisateur donné. 
     * 
     */
     
    private function renderUserTweets()
    {

        /* 
         * Retourne le fragment HTML pour afficher
         * tous les Tweets d'un utilisateur donné. 
         *  
         * L'attribut $this->data contient un objet User.
         *
         */
        $r = new \mf\router\Router();
        $chaines = "<div>";
        //echo "<br>".$this->data[0]->author()->first()->fullname;
        foreach($this->data as $v)
        {
            $hrefTweet = $r->urlFor("view",array(["id",$v->id]));
            $chaines .="<div class=\"tweet\"><a href=\"$hrefTweet\">".$v->text.' Crée le : '.$v->created_at.' de : '.$v->author()->first()->fullname."</div>";
        }
        return $chaines."<div>";
    }
  
    /* Méthode renderViewTweet 
     * 
     * Rréalise la vue de la fonctionnalité affichage d'un tweet
     *
     */
    
    private function renderViewTweet()
    {

        /* 
         * Retourne le fragment HTML qui réalise l'affichage d'un tweet 
         * en particulié 
         * 
         * L'attribut $this->data contient un objet Tweet
         *
         */
        $r = new \mf\router\Router();
        $chaines = "<div>";
        $hrefTweet = $r->urlFor("user",array(["id",$this->data->author]));
        $chaines .="<div class=\"tweet\"><a href=\"$hrefTweet\">".$this->data->text.'</a><br>id = '.$this->data->id.' Crée le : '.$this->data->created_at." de : ".$this->data->author()->first()->fullname."</div>";
        return $chaines."</div>";
    }



    /* Méthode renderPostTweet
     *
     * Realise la vue de régider un Tweet
     *
     */
    private function renderPostTweet(){
        
        /* Méthode renderPostTweet
         *
         * Retourne la framgment HTML qui dessine un formulaire pour la rédaction 
         * d'un tweet, l'action (bouton de validation) du formulaire est la route "/send/"
         *
         */
        $r = new \mf\router\Router();
        $urlForPost = $r->urlFor('send',null);
        $formulaire = <<<EOT
        <form method="post" action="${urlForPost}">
            <textarea class="tweet-forms" name="value">
            </textarea>
            <br>
            <input type="submit" value="Envoyer">
        </form>
EOT;
        return $formulaire;
    }

    private function renderFollowers()
    {
        $r = new \mf\router\Router();
        $chaines = "<div>";
        $chaines .= "<h1>Vous êtes follow par : </h1>";
        foreach($this->data as $user)
        {
            $hrefUser = $r->urlFor("user",array(["id",$user->id]));
            $chaines .= "<a href=\"$hrefUser\">".$user->fullname."</a><br>";
        }
        return $chaines."</div>";
    }
    private function renderSend()
    {
        return "<div>Tweet ajouté à la base de données avec succés!</div>";
    }

    private function renderSignup()
    {
        $r = new \mf\router\Router();
        $urlCheckSignup = $r->urlFor("checksignup");
        $formulaire = <<<EOT
        <form method="post" action="${urlCheckSignup}">
        <div>
            <input type="text" name="fullname" placeholder="Nom Complet" required>
        </div>
        <div>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        </div>
        </div>
            <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <div>
            <input type="submit" value="S'inscrire">
        </div>
        </form>
EOT;
        return $formulaire;
    }

    private function renderLogin()
    {
        $r = new \mf\router\Router();
        $urlCheckLogin = $r->urlFor("checkLogin");
        $formulaire = <<<EOT
        <form method="post" action="${urlCheckLogin}">
        <div>
            <input type="text" name="username" placeholder="Nom d'utilisateur">
        </div>
        </div>
            <input type="password" name="password" placeholder="Mot de passe">
        </div>
        <div>
            <input type="submit" value="Connexion">
        </div>
        </form>
EOT;
        return $formulaire;
    }

    private function renderBottomMenu()
    {
        $auth = new \tweeterapp\auth\TweeterAuthentification();
        if($auth->logged_in)
        {
            $r = new \mf\router\Router();
            $urlPost = $r->urlFor("post");
            $chaines = <<<EOT
            <nav class="theme-backcolor1">
                <a class="theme-backcolor2" href="${urlPost}">
                    New
                </a>
            </nav>    
EOT;
            return $chaines;
        }
    }
    protected function renderBody($selector)
    {
        /*
         * voir la classe AbstractView
         * 
         */
        $header = $this->renderHeader();
        $TopMenu = $this->renderTopMenu();
        $section ="";
        switch($selector){
            case 'maison':
                $section = $this->renderHome();
            break;
            case 'user':
                $section = $this->renderUserTweets();
            break;
            case 'view':
                $section = $this->renderViewTweet();
            break;
            case 'post':
                $section = $this->renderPostTweet();
            break;
            case 'send':
                $section = $this->renderSend();
            break;
            case 'login':
                $section = $this->renderLogin();
            break;
            case 'signup':
                $section = $this->renderSignup();
            break;
            case 'follow':
                $section = $this->renderFollowers();
            break;
        }
        $NewTweet = $this->renderBottomMenu();
        $footer = $this->renderFooter();
        $html = <<<EOT
        <header class="theme-backcolor1">  
            ${header}
            ${TopMenu}
        </header>
        <section class="theme-backcolor2">
            ${section}
        </section>
            ${NewTweet}
        <footer class="theme-backcolor1">
            ${footer}
        </footer>
EOT;
        return $html;
    } 
}
