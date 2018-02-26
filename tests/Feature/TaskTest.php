<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\TaskStatus;
use App\Task;
use App\Tag;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $task;
    protected $name = 'new task';
    protected $status;
    protected $creator;
    protected $assignedTo;
    protected $tag1;
    protected $tag2;


    protected function setUp()
    {
        parent::setUp();

        $this->status = factory(TaskStatus::class)->create();

        $users = factory(User::class, 2)->create();
        list($this->creator, $this->assignedTo) = $users;

        $tags = factory(Tag::class, 2)->create();
        list($this->tag1, $this->tag2) = $tags;
    }

    protected function createTask()
    {
        $task = new Task;

        $task->name = 'task name';
        $task->description = 'task description';
        $task->status()->associate($this->status);
        $task->creator()->associate($this->creator);
        $task->assignedTo()->associate($this->assignedTo);

        $task->save();

        $task->tags()->attach($this->tag1->id);
        $task->tags()->attach($this->tag2->id);


        $this->task = $task;
    }

    /**
     * Create user.
     *
     * @return void
     */
    public function testCreateTask()
    {
        $this->actingAs($this->creator)
             ->get(route('tasks.create'))
             ->assertSeeTextInOrder([
                 'Name',
                 'Description',
                 'Task Status',
                 'Assigned To',
                 'Tags',
                 'Create',
             ]);

        $this->actingAs($this->creator)
             ->withSession(['_token' => 'secret' ])
             ->post(route('tasks.store'), [
                 '_token' => csrf_token(),
                 'name' => $this->name,
                 'description' => '',
                 'status_id' => $this->status->id,
                 'assignedTo_id' => $this->assignedTo->id,
                 'tags' => "{$this->tag1->name}, {$this->tag2->name}",
             ])
             ->assertRedirect(route('tasks.index'));

        $task = Task::where('name', $this->name)->first();
        $tags = $task->tags;

        $this->assertEquals($this->status->id, $task->status->id);
        $this->assertEquals($this->creator->id, $task->creator->id);
        $this->assertEquals($this->tag1->id, $tags->first()->id);
        $this->assertEquals($this->tag2->id, $tags->last()->id);
    }


    /**
     * Edit user.
     *
     * @return void
     */
    public function testEditTask()
    {
        $this->createTask();

        $task = $this->task;

        $this->actingAs($this->creator)
             ->get(route('tasks.edit', $task->id))
             ->assertSeeTextInOrder([
                 'Name',
                 'Description',
                 'Task Status',
                 'Assigned To',
                 'Tags',
                 'Save',
                 'Delete',
             ])
             ->assertSeeInOrder([
                 'Name',
                 $task->name,
                 'Description',
                 $task->description,
                 'Task Status',
                 $task->status->name,
                 'Assigned To',
                 $task->assignedTo->name,
                 'Tags',
                 tagsList($task),
             ]);


        $newName = $task->name . 'test';

        $this->actingAs($this->creator)->withSession(['_token' => 'secret' ])
             ->post(route('tasks.update', $task->id), [
                 '_token' => csrf_token(),
                 '_method' => 'PUT',
                 'name' => $newName,
                 'description' => '',
                 'status_id' => $task->status->id,
                 'assignedTo_id' => $task->assignedTo->id,
                 'tags' => $this->tag1->name,
             ])
             ->assertRedirect(route('tasks.index'));


        $editedTask = Task::find($task->id);
        $editedTags = $editedTask->tags;

        $this->assertEquals($newName, $editedTask->name);
        $this->assertEquals(1, $editedTags->count());
        $this->assertDatabaseMissing('tags', [
            'id' => $this->tag2->id
        ]);
    }

    /**
     * Delete user.
     *
     * @return void
     */
    public function testDeleteTask()
    {
        $this->createTask();

        $task = $this->task;

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);

        $this->actingAs($this->creator)->withSession(['_token' => 'secret' ])
             ->post(route('tasks.destroy', $task->id), [
                 '_token' => csrf_token(),
                 '_method' => 'DELETE'
             ])
             ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
