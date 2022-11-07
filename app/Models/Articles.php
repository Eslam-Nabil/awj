<?php

namespace App\Models;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model implements TranslatableContract
{
    use Translatable,HasFactory;
    protected $guarded=[];
    public $translatedAttributes = ['title', 'description'];

}