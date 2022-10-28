<?php

namespace App\Models;

use App\Models\SectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdditionalSection extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function page()
    {
        return $this->belongsTo(Pages::class,'pages_id');
    }
    public function type()
    {
        return $this->belongsTo(SectionType::class,'section_types_id');
    }
}
