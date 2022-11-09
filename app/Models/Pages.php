<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Pages extends Model implements TranslatableContract
{
    use Translatable,HasFactory;

    protected $table = 'pages';
    public $translatedAttributes = ['page_name', 'slug','main_title','meta_title','meta_description','slogan','main_description','second_description'];
    protected $fillable  = ['main_image_path','second_image_path'];

    public function additional_section()
    {
        return $this->hasMany(AdditionalSection::class);
    }
}
