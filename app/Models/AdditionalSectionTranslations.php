<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalSectionTranslations extends Model
{
    use HasFactory;
    
    protected $table = 'additional_section_translations';
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
