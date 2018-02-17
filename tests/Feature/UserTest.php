<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Create user.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->make();
        $this->assertGuest();
        $this->withSession(['_token' => 'secret' ])
             ->post(route('register'), [
                 '_token' => csrf_token(),
                 'name' => $user->name,
                 'email' => $user->email,
                 'password' => $user->password,
                 'password_confirmation' => $user->password
             ])
             ->assertRedirect(route('users.index'));

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

        $this->withSession(['_token' => 'secret' ])
             ->post(route('login'), [
                 '_token' => csrf_token(),
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

        $this->actingAs($user)->withSession(['_token' => 'secret' ])
             ->post(route('users.update', $user->id), [
                 '_token' => csrf_token(),
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

        $this->actingAs($user)->withSession(['_token' => 'secret' ])
             ->post(route('users.destroy', $user->id), [
                 '_token' => csrf_token(),
                 '_method' => 'DELETE'
             ])
             ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
