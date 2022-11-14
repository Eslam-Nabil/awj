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
    protected $fillable = ['image_path','type_id','additionalsectionable_id','additionalsectionable_type','section_types_id'];

    public function additionalsectionable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(SectionType::class,'section_types_id');
    }
}