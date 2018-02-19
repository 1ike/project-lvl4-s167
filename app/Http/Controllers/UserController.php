<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Where to redirect users after update or delete.
     *
     * @return \Illuminate\Http\Response
     */
    protected function redirectTo()
    {
        return redirect(route('users.index'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Users';
        $users = User::paginate(10);

        return view('users.index', [
            'title' => $title,
            'users' => $users,
        ]);
    }


    /**
     * Check is the user author or admin.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    protected function checkPermissions(User $user)
    {
        $this->middleware('auth');
        $this->authorize('edit-user', $user);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->checkPermissions($user);

        $title = 'Edit user profile';

        return view('users.edit', [
            'title' => $title,
            'user' => $user,
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->checkPermissions($user);

        $data = $request->all();

        $this->validator($data)->validate();

        $user->name = $data['name'];
        $user->email =$data['email'];
        $user->password =bcrypt($data['password']);
        $user->save();

        flash("Profile&nbsp; \"$user->name\" &nbsp;was updated!");

        return $this->redirectTo();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->checkPermissions($user);

        flash("Profile&nbsp; \"$user->name\" &nbsp;was deleted!");
        $user->delete();

        return $this->redirectTo();
    }
}
