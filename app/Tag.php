<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];


    /**
     * Get the task record associated with the tag.
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'task_tag');
    }
}
