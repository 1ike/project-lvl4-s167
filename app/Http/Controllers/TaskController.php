<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskStatus;
use App\User;
use Illuminate\Http\Request;
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
        // $request->status_id = 100;
        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            DB::commit();
            return redirect(route('tasks.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $this->saveTask($request, $task = Task::make());

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

        $request->validate($this->validationsRules);

        $task->name = $request->name;
        $task->save();

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
