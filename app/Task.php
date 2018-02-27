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

    /**
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filterState
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filterState)
    {

        $whereQuery = array_reduce($filterState, function ($acc, $item) {
            switch ($item['type']) {
                case 'where':
                    return $acc->where($item['column'], $item['value']);
                case 'whereHas':
                    return $acc->whereHas($item['name'], function ($query) use ($item) {
                        $query->where($item['column'], $item['value']);
                    });
                default:
                    $v = $item['value'];
                    $val = $item['value'] == 'asc';
                    $value = $item['value'] == 'asc' ? 'asc' : 'desc' ;
                    return $acc->orderBy($item['column'], $value);
            }

            return $acc;
        }, $query);

        return $whereQuery;
    }
}
