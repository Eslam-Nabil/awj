<?php

namespace App\Models;

use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionType extends Model
{
    use HasFactory;
    protected $table ='section_types';
    protected $guarded  = [];


    public function section()
    {
        return $this->hasMany(Section::class,'section_types_id');
    }

}
