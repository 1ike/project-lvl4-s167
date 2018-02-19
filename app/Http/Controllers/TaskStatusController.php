<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskStatus;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskStatusController extends Controller
{
    /**
     * Validations rules.
     *
     * @var array
     */
    protected $validationsRules = [
        'name' => 'required|string|max:255|unique:task_statuses',
    ];

    /**
     * Where to redirect users after update or delete.
     *
     * @return \Illuminate\Http\Response
     */
    protected function redirectTo()
    {
        return redirect(route('taskstatuses.index'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('taskstatuses.index', [
            'taskstatuses' => TaskStatus::paginate(10),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('edit-taskstatus');

        return view('taskstatuses.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('edit-taskstatus');

        $request->validate($this->validationsRules);

        $taskstatus = TaskStatus::create([
            'name' => $request->name,
        ]);

        flash("Task Status&nbsp; \"$taskstatus->name\" &nbsp;was created!");

        return $this->redirectTo();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskStatus  $taskstatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskStatus $taskstatus)
    {
        $this->authorize('edit-taskstatus');

        return view('taskstatuses.edit', [
            'taskstatus' => $taskstatus,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskStatus  $taskstatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskStatus $taskstatus)
    {
        $this->authorize('edit-taskstatus');

        $request->validate($this->validationsRules);

        $taskstatus->name = $request->name;
        $taskstatus->save();

        flash("Task Status&nbsp; \"$taskstatus->name\" &nbsp;was updated!");

        return $this->redirectTo();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskStatus  $taskstatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskStatus $taskstatus)
    {
        $this->authorize('edit-taskstatus');

        flash("Task Status&nbsp; \"$taskstatus->name\" &nbsp;was deleted!");
        $taskstatus->delete();

        return $this->redirectTo();
    }
}
