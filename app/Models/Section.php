<?php

namespace App\Models;

use App\Models\SectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Section extends Model implements TranslatableContract
{
    use Translatable,HasFactory;
    protected $table='sections';
    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['image_path','sectionable_id','sectionable_type','section_types_id'];

    public function sectionable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(SectionType::class,'section_types_id');
    }
}
