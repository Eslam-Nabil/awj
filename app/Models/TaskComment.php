<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function task()
    {
        return $this->belongsTo(user_task::class, 'user_task_id');
    }

}
