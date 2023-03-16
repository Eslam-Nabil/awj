<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable=['article_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function article()
    {
        return $this->hasMany(User::class, 'article_id');
    }
}
