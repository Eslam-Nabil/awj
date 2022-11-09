<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pagesTranslation extends Model
{
    use HasFactory;
    protected $table = 'page_translations';
    public $timestamps = false;
    protected $fillable = ['page_name', 'slug','main_title','meta_title','meta_description','slogan','main_description','second_description'];
}
