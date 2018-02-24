<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use App\TaskStatus;
use App\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Validations rules.
     *
     * @var array
     */
    protected $validationsRules = [
        'name' => 'required|string|max:255',
        'status_id' => 'required|integer',
        'assignedTo_id' => 'required|integer',
    ];

    /**
     * Task Status.
     *
     * @var \App\TaskStatus
     */
    protected $status;

    /**
     * AssignedTo user.
     *
     * @var \App\User
     */
    protected $assignedTo;

    /**
     * Tags list.
     *
     * @var array
     */
    protected $tagsList;

    /**
     * Current tags collection.
     *
     * @var array
     */
    protected $tags;


    /**
     * Previous tags collection.
     *
     * @var array
     */
    protected $previousTags;

    /**
     * New tags collection.
     *
     * @var array
     */
    protected $tagsIncrement;

    /**
     * Removed tags collection.
     *
     * @var array
     */
    protected $tagsDecrement;


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('create', 'store');
    }

    /**
     * Where to redirect users after update or delete.
     *
     * @return \Illuminate\Http\Response
     */
    protected function redirectTo()
    {
        return redirect(route('tasks.index'));
    }

    /**
     * Prepare validator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Validation\Validator  $validator
     */
    protected function getValidator(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->validationsRules
        );

        $this->status = TaskStatus::find($request->status_id);
        $this->assignedTo = User::find($request->assignedTo_id);

        $validator->after(function ($validator) {
            if (!$this->status) {
                $validator->errors()->add(
                    'status_id',
                    'There is no such status. May be it has been deleted. Choose status again.'
                );
            }
            if (!$this->assignedTo) {
                $validator->errors()->add(
                    'assignedTo_id',
                    'There is no such user. May be user has been deleted. Choose user again.'
                );
            }
        });

        return $validator;
    }


    /**
     * Save task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return void
     */
    protected function saveTask(Request $request, Task $task)
    {
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status()->associate($this->status);
        $task->assignedTo()->associate($this->assignedTo);
        $task->creator()->associate(Auth::user());

        $task->save();

        $tagsListRaw = explode(',', $request->tags);
        $trimmedList = array_map(function ($tagName) {
            return trim($tagName);
        }, $tagsListRaw);

        $this->tagsList = collect($trimmedList)
            ->reject(function ($name) {
                return empty($name);
            })
            ->unique()
            // ->values()
            ->all();

        $tags = array_map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName]);
        }, $this->tagsList);
        $this->tags = new Collection($tags);
    }


    /**
     * Save tags.
     *
     * @param  \App\Task  $task
     * @param  \Illuminate\Support\Collection  $tags
     * @return void
     */
    protected function saveNewTags(Task $task, Collection $tags)
    {
        foreach ($tags as $tag) {
            $task->tags()->attach($tag->id);
        }
    }

    /**
     * Remove tags.
     *
     * @param  \App\Task  $task
     * @return void
     */
    protected function removeOldTags(Task $task)
    {
        foreach ($this->tagsDecrement as $tag) {
            $task->tags()->detach($tag->id);
            if ($tag->tasks()->count()) {
                $tag->delete();
            }
        }
    }

    /**
     * Prepare tags for update.
     *
     * @param  \App\Task  $task
     * @return void
     */
    protected function prepareTagsForUpdate(Task $task)
    {
        $this->previousTags = $task->tags;
        $this->tagsIncrement = $this->tags->diff($this->previousTags);
        $this->tagsDecrement = $this->previousTags->diff($this->tags);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tasks.index', [
            'tasks' => Task::paginate(10),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create', [
            'statuses' => TaskStatus::all(),
            'users' => User::all(),
            'task' => Task::make()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            DB::commit();
            return redirect(route('tasks.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $this->saveTask($request, $task = Task::make());
        $this->saveNewTags($task, $this->tags);

        DB::commit();

        flash("Task&nbsp; \"$task->name\" &nbsp;was created!");

        return $this->redirectTo();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->authorize('edit-task', $task);

        return view('tasks.edit', [
            'statuses' => TaskStatus::all(),
            'users' => User::all(),
            'task' => $task,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('edit-task', $task);

        DB::beginTransaction();

        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            DB::commit();
            return redirect(route('tasks.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $this->saveTask($request, $task);
        $this->prepareTagsForUpdate($task);
        $this->saveNewTags($task, $this->tagsIncrement);
        $this->removeOldTags($task);

        DB::commit();

        flash("Task&nbsp; \"$task->name\" &nbsp;was updated!");

        return $this->redirectTo();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $this->authorize('edit-task', $task);

        $task->delete();

        flash("Task&nbsp; \"$task->name\" &nbsp;was deleted!");

        return $this->redirectTo();
    }
}
