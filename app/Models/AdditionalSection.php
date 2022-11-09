<?php

namespace App\Models;

use App\Models\SectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class AdditionalSection extends Model implements TranslatableContract
{
    use Translatable,HasFactory;
    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['image_path'];

    public function page()
    {
        return $this->belongsTo(Pages::class,'pages_id');
    }
    public function type()
    {
        return $this->belongsTo(SectionType::class,'section_types_id');
    }
}
