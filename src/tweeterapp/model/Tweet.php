<?php
namespace tweeterapp\model;
class Tweet extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'tweet';  /* le nom de la table */
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public $timestamps = true; 
    public function author()
    {
        return $this->belongsTo('tweeterapp\model\User','author');
    }
    public function likedBy()
    {
        //1er param: nom du modèle de la table visée
        //2ème param: nom de la table pivot
        //3ème param: nom de la clé étrangère de la table de départ
        //4ème param: nom de la clé étrangère de la table d'arrivée
        return $this->belongsToMany('tweeterapp\model\User', 'tweeterapp\model\Like', 'tweet_id', 'user_id');
    }
}