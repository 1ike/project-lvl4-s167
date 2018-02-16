<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Pages accessible w/o auth.
     *
     * @return void
     */
    public function testGuest()
    {
        $this->get('/')
             ->assertStatus(200)
             ->assertSeeText('Meet yet another Task Manager!');

        $this->get('/login')
             ->assertStatus(200)
             ->assertSee("<button type=\"submit\" class=\"btn btn-primary\">\n                                    Login\n                                </button>");

        $this->get(route('register'))
             ->assertStatus(200)
             ->assertSeeTextInOrder([
                 'Name',
                 'E-Mail Address',
                 'Password',
                 'Confirm Password',
                 'Register'
             ]);

        $this->get('/password/reset')
             ->assertStatus(200);

        $this->get(route('users.index'))
             ->assertStatus(200)
             ->assertSeeText('There are no users yet.');
    }
}
