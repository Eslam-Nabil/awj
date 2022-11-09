<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesTranslations extends Model
{
    use HasFactory;
    protected $table = 'categories_translations';
    public $timestamps = false;
    protected $fillable = ['title'];
}