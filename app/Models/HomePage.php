<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable=[
        'page_name','first_section_title','first_side_image','third_section_title',
        'third_left_description','third_right_description','third_side_image'
    ];

}
