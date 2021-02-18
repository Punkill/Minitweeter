<?php

namespace tweeterapp\control;

/* Classe TweeterController :
 *  
 * Réalise les algorithmes des fonctionnalités suivantes: 
 *
 *  - afficher la liste des Tweets 
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur 
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis 
 *  - évaluer un Tweet
 *  - suivre un utilisateur
 *   
 */

class TweeterController extends \mf\control\AbstractController {


    /* Constructeur :
     * 
     * Appelle le constructeur parent
     *
     * c.f. la classe \mf\control\AbstractController
     * 
     */
    
    public function __construct(){
        parent::__construct();
    }


    /* Méthode viewHome : 
     * 
     * Réalise la fonctionnalité : afficher la liste de Tweet
     * 
     */
    
    public function viewHome()
    {
        /*$follow = new Follow();
        $follow->follower = 10;
        $user = new User();
        $user->id = 11;
        $user->fullname = "Kremer Thomas";
        $user->username = "Punkill";
        $user->password = "Bonjour";
        $user->level = 120;


    /* Méthode viewUserTweets :
     *
     * Réalise la fonctionnalité afficher les tweet d'un utilisateur
     *
     */
    /*
        $user->followers = 66;
        echo $user;
        $like = new Like();
        $like->user_id = 11;
        echo $like;
        $tweet = new Tweet();
        $tweet->text = "Ceci est un tweet de test";
        $tweet->author = 11;
        $tweet->score = 1;
        echo $tweet;

        $requete = User::select();
        $lignes = $requete->get();

        foreach($lignes as $v)
            echo "<br>ID = $v->id, fullname = $v->fullname, username = $v->username, password = $v->password, level = $v->level, followers = $v->followers";

        $requete = Tweet::select();
        $lignes = $requete->get();

        foreach($lignes as $v)
            echo "<br>ID = $v->id, text = $v->text";

        //Tri des tweets par date de création 
        $lignes = Tweet::select()
                        ->orderBy('created_at')
                        ->get();
        foreach($lignes as $v)
            echo "<br>ID = $v->id, text = $v->text, crée le = $v->created_at";

        //Affichage tweet avec score positif
        $requete = Tweet::select()->where('score','>=',1);
        $lignes = $requete->get();
        foreach($lignes as $v)
            echo "<br>ID = $v->id, text = $v->text, score = $v->score";

        $user->save();
        $tweet->save();*/
        
        /*$requete = \tweeterapp\model\Tweet::select();
        $lignes = $requete->get();
        foreach($lignes as $v)
        {
            /*$r = new \mf\router\Router();
            $href = $r->urlFor("view",array(["id",$v->id]));
            echo "Texte = $v->text, Auteur = $v->author, Crée le : $v->created_at<br>";
        }*/
        $tweets = \tweeterapp\model\Tweet::all();
        $vue = new \tweeterapp\view\TweeterView($tweets);
        $vue->render('maison');
        /* Algorithme :
         *  
         *  1 Récupérer tout les tweet en utilisant le modèle Tweet
         *  2 Parcourir le résultat 
         *      afficher le text du tweet, l'auteur et la date de création
         *  3 Retourner un block HTML qui met en forme la liste
         * 
         */

    }


    /* Méthode viewTweet : 
     *  
     * Réalise la fonctionnalité afficher un Tweet
     *
     */
    
    public function viewTweet(){

        /* Algorithme : 
         *  
         *  1 L'identifiant du Tweet en question est passé en paramètre (id) 
         *      d'une requête GET 
         *  2 Récupérer le Tweet depuis le modèle Tweet
         *  3 Afficher toutes les informations du tweet 
         *      (text, auteur, date, score)
         *  4 Retourner un block HTML qui met en forme le Tweet
         * 
         *  Erreurs possibles : (*** à implanter ultérieurement ***)
         *    - pas de paramètre dans la requête
         *    - le paramètre passé ne correspond pas a un identifiant existant
         *    - le paramètre passé n'est pas un entier 
         * 
         */
        $id = $this->request->get;
        $requete = \tweeterapp\model\Tweet::select()->where('id','=',$id);
        $tweet = $requete->first();
        $vue = new \tweeterapp\view\TweeterView($tweet);
        $vue->render('view');
    }   


    /* Méthode viewUserTweets :
     *
     * Réalise la fonctionnalité afficher les tweet d'un utilisateur
     *
     */
    
    public function viewUserTweets(){

        /*
         *
         *  1 L'identifiant de l'utilisateur en question est passé en 
         *      paramètre (id) d'une requête GET 
         *  2 Récupérer l'utilisateur et ses Tweets depuis le modèle 
         *      Tweet et User
         *  3 Afficher les informations de l'utilisateur 
         *      (non, login, nombre de suiveurs) 
         *  4 Afficher ses Tweets (text, auteur, date)
         *  5 Retourner un block HTML qui met en forme la liste
         *
         *  Erreurs possibles : (*** à implanter ultérieurement ***)
         *    - pas de paramètre dans la requête
         *    - le paramètre passé ne correspond pas a un identifiant existant
         *    - le paramètre passé n'est pas un entier 
         * 
         */
        $id = $this->request->get;
        $user = \tweeterapp\model\User::where('id','=',$id)->first();
        $liste_tweet = $user->tweets()->get();
        $vue = new \tweeterapp\view\TweeterView($liste_tweet);
        $vue->render('user');
    }
    public function viewPost()
    {
        $vue = new \tweeterapp\view\TweeterView(null);
        $vue->render('post');
    }
    public function Send()
    {
        $user = \tweeterapp\model\User::where('username','=',$_SESSION['user_login'])->first();
        $tweet = new \tweeterapp\model\Tweet();
        $tweet->text = filter_var($this->request->post['value'],FILTER_SANITIZE_SPECIAL_CHARS);
        $tweet->author = $user->id;
        $tweet->score = 0;
        $tweet->save();
        $vue = new \tweeterapp\view\TweeterView(null);
        $vue->render('send');
    }

    public function viewFollowers()
    {
        $user = \tweeterapp\model\User::where('username','=',$_SESSION['user_login'])->first();
        $followers = $user->followedBy()->get();
        $vue = new \tweeterapp\view\TweeterView($followers);
        $vue->render('follow');
    }

    public function viewFollowing()
    {
        $user = \tweeterapp\model\User::where('username','=',$_SESSION['user_login'])->first();
        $follow = $user->follows()->get();
        $userTweets = array();
        foreach($follow as $suiveur)
        {
            $userTweets[$suiveur->username] = $suiveur->tweets()->get();
        }
        //print_r($userTweets);
        foreach($userTweets as $key =>$tweets)
        {
            echo $key;
            foreach($tweets as $tweet)
            {
                echo $tweet."<br>";
            }
        }
        //$vue = new \tweeterapp\view\TweeterView($follow);
        //$vue->render('follow');
    }

    public function viewUserfollow()
    {
        $users = \tweeterapp\model\User::select()->orderBy('followers','DESC')->get();
        foreach($users as $user)
            echo $user->username.' '.$user->followers."<br>";
    }
}
