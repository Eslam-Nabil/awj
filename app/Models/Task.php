<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
class Task extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $fillable=['file_path','duration'];
    public $translatedAttributes = ['title', 'description'];
   /**
    * Get the article that owns the Task
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function article()
   {
    return $this->belongsTo(Article::class, 'article_id');
   }
   public function users()
   {
       return $this->belongsToMany(User::class,'user_tasks')->withPivot('delivery_date','file_path','student_comment','status','delay')->withTimestamps();
   }

}
