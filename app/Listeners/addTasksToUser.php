<?php

namespace App\Listeners;

use App\Events\BuyArticle;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class addTasksToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BuyArticle $event)
    {
        $user=$event->user;
        $tasks = Article::find($event->articleId)->tasks;
        if(!empty($tasks)){
            foreach($tasks as $task_col){
                $delivery_date['delivery_date']=Carbon::now()->addDays($task_col->duration)->format("Y-m-d");
                $delivery_date['status']='ToDo';
                $user->tasks()->attach([$task_col->id],$delivery_date);
            }
        }
    }
}
