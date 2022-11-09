<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Categories extends Model implements Translatable
{
    use Translatable,HasFactory;

    protected $table='categories';
    public $translatedAttributes=['title'];
}