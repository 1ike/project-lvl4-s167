<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;

class UserTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $email = 'vasiliy@pupkin.com';
    protected $usersURL = '/users';

    /**
     * Create User.
     *
     * @return void
     */
    public function testCreate()
    {
        $user = factory(User::class)->make([
            'email' => $this->email
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $user->email
            ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/')
            ->clickLink('Register')
            ->assertPathIs('/register')
            ->type('name', $user->name)
            ->type('email', $user->email)
            ->type('password', $user->password)
            ->type('password_confirmation', $user->password)
            ->press('Register')
            ->assertPathIs($this->usersURL);
        });

        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    /**
     * Login/logout User.
     *
     * @return void
     */
    public function testLogin()
    {
        $pass = 123;
        $user = factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);

        $this->browse(function (Browser $browser) use ($user, $pass) {
            $browser->visit('/')
            ->clickLink($user->name)
            ->clickLink('Logout')
            ->assertPathIs('/')
            ->clickLink('Login')
            ->assertPathIs('/login')
            ->type('email', $user->email)
            ->type('password', $pass)
            ->press('Login')
            ->assertPathIs($this->usersURL);
        });
    }

    /**
     * Edit/Delete User.
     *
     * @return void
     */
    public function testEditDelete()
    {
        $pass = 123456;
        $name = 'Uasyaaa';
        $user = factory(User::class)->create([
            'name' => $name,
            'password' => bcrypt($pass)
        ]);

        $this->browse(function (Browser $browser) use ($user, $pass) {
            $browser->visit('/')
            ->clickLink($user->name)
            ->clickLink('Edit')
            ->assertPathIs("/users/$user->id/edit")
            ->type('name', "test $user->name")
            ->type('email', $user->email)
            ->type('password', $pass)
            // ->type('password_confirmation', $pass)
            ->press('Save changes')
            ->assertPathIs($this->usersURL)
            ->clickLink("test $user->name")
            ->clickLink('Edit')
            ->press('Delete profile')
            ->assertDialogOpened('Are you realy want delete profile?')
            ->acceptDialog()
            ->assertPathIs($this->usersURL);
        });


    }
}
