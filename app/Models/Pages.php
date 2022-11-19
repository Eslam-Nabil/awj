<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\SectionTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;


class Pages extends Model implements TranslatableContract
{
    use Translatable,HasFactory;

    protected $table = 'pages';
    public $translatedAttributes = ['page_name', 'slug','main_title','meta_title','meta_description','slogan','main_description','second_description'];
    protected $fillable  = ['main_image_path','second_image_path'];

    // public function additional_section()
    // {
    //     return $this->hasMany(AdditionalSection::class);
    // }
    public function sections()
   {
        return $this->morphMany(SectionTranslations::class, 'sectionable');
   }
}
