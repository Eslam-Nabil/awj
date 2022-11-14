<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Article extends Model implements TranslatableContract
{
    use Translatable,HasFactory;
    protected $fillable=[
        'user_id','article_file_path','audio_file_path',
        'cover_file_path','price','category_id','status','isApproved',
        'pages_count'
    ];
    public $translatedAttributes = ['title', 'description','summary','language'];


   /**
    * Get the user that owns the Articles
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   /**
    * Get all of the comments for the Articles
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function comments()
   {
       return $this->hasMany(Comment::class, 'article_id');
   }

   public function sections()
   {
    return $this->morphMany(AdditionalSection::class, 'additionalsectionable');
   }
}