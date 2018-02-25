<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\TaskStatus;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * Create user.
     *
     * @return void
     */
    public function testCreateTaskStatus()
    {
         $this->actingAs($this->user)
             ->get(route('taskstatuses.create'))
             ->assertSeeTextInOrder([
                'Name',
                'Create',
            ]);


        $name = 'new';

        $this->actingAs($this->user)
             ->withSession(['_token' => 'secret' ])
             ->post(route('taskstatuses.store'), [
                 '_token' => csrf_token(),
                 'name' => $name,
             ])
             ->assertRedirect(route('taskstatuses.index'));

        $this->assertDatabaseHas('task_statuses', [
            'name' => $name,
        ]);
    }


    /**
     * Edit user.
     *
     * @return void
     */
    public function testEditTaskStatus()
    {
        $status = factory(TaskStatus::class)->create();
        $newName = $status->name . 'test';

        $this->actingAs($this->user)
        ->get(route('taskstatuses.edit', $status->id))
        ->assertSeeTextInOrder([
           'Name',
           'Save',
           'Delete',
        ]);

        $this->actingAs($this->user)->withSession(['_token' => 'secret' ])
             ->post(route('taskstatuses.update', $status->id), [
                 '_token' => csrf_token(),
                 '_method' => 'PUT',
                 'name' => $newName,
             ])
             ->assertRedirect(route('taskstatuses.index'));

        $this->assertDatabaseHas('task_statuses', [
            'name' => $newName
        ]);
    }

    /**
     * Delete user.
     *
     * @return void
     */
    public function testDeleteTaskStatus()
    {
        $status = factory(TaskStatus::class)->create();

        $this->assertDatabaseHas('task_statuses', [
            'id' => $status->id,
        ]);

        $this->actingAs($this->user)->withSession(['_token' => 'secret' ])
             ->post(route('taskstatuses.destroy', $status->id), [
                 '_token' => csrf_token(),
                 '_method' => 'DELETE'
             ])
             ->assertRedirect(route('taskstatuses.index'));

        $this->assertDatabaseMissing('task_statuses', [
            'id' => $status->id
        ]);
    }
}
