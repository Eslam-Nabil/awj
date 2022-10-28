<?php

namespace App\Models;

use App\Models\AdditionalSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionType extends Model
{
    use HasFactory;
    protected $table ='section_types';

    public function additional_section()
    {
        return $this->hasMany(AdditionalSection::class);
    }

}
