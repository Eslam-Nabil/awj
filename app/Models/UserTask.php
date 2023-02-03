<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTask extends Pivot
{
    use HasFactory;
    public $table='user_tasks';
    public function users(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->belongsTo(Task::class);
    }

    public function comments(){
        return $this->hasMany(TaskComment::class, 'user_task_id');
    }

}
