<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Comment;
use App\Models\SocialMedia;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Translatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class User extends Authenticatable implements TranslatableContract
{
    use Translatable,HasApiTokens,HasRoles,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public  $translatedAttributes=['about','title'];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'emirateid',
        'birthdate',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the articles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function article()
    {
        return $this->hasMany(Article::class,'user_id');
    }
    public function cart()
    {
        return $this->hasMany(Cart::class,'user_id');
    }

    public function socialmedia()
    {
        return $this->hasMany(SocialMedia::class,'user_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class,'user_articles')->withPivot('is_free','order_id','order_status','price'/*,'certificate'*/)->withTimestamps();
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class,'user_tasks')->withPivot('id','delivery_date','delivered_date','file_path','student_comment','status','delay')->withTimestamps();
    }

    public function paypalOrder()
    {
        return $this->hasMany(PaypalOrder::class,'user_id');
    }
}
