<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTranslation extends Model
{
    use HasFactory;
    protected $table = 'user_translations';
    public $timestamps = false;
    protected $fillable=['about','title'];
}
