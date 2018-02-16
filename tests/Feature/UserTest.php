<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create user.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->make();

        $this->post(route('register'), [
                 'name' => $user->name,
                 'email' => $user->email,
                 'password' => $user->password,
                 'password_confirmation' => $user->password
             ]);
            //  ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    /**
     * Login/logout user.
     *
     * @return void
     */
    public function testLoginUser()
    {
        $pass = '123456';
        $user = factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);

        $this->assertGuest();

        $this->post(route('login'), [
                 'email' => $user->email,
                 'password' => $pass
             ])
             ->assertRedirect(route('users.index'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
             ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    /**
     * Edit user.
     *
     * @return void
     */
    public function testEditUser()
    {
        $pass = '123456';
        $user = factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);
        $newName = $user->name . 'test';

        $this->actingAs($user)
             ->post(route('users.update', $user->id), [
                 '_method' => 'PUT',
                 'name' => $newName,
                 'email' => $user->email,
                 'password' => $pass
             ])
             ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName
        ]);
    }

    /**
     * Delete user.
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $this->actingAs($user)
             ->post(route('users.destroy', $user->id), [
                 '_method' => 'DELETE'
             ])
             ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
