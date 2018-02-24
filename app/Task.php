<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];


    /**
     * Get the status record associated with the task.
     */
    public function status()
    {
        return $this->belongsTo('App\TaskStatus', 'status_id');
    }


    /**
     * Get the creator user record associated with the task.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }


    /**
     * Get the assignedTo user record associated with the task.
     */
    public function assignedTo()
    {
        return $this->belongsTo('App\User', 'assignedTo_id');
    }


    /**
     * Get the tag record associated with the task.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'task_tag');
    }
}
