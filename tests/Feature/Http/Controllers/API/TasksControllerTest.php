<?php

use App\Models\Project;
use App\Models\Tasks;

it('does not load any tasks', function () {
    $response = $this->get('/api/tasks');

    $response->assertStatus(404);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(2)
        ->success->toBe(false);
});

it('does load tasks', function () {
    Tasks::factory(5)->create();
    $response = $this->get('/api/tasks');

    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->toHaveCount(5);
});

it('does filter tasks by project id', function () {
    $project = Project::factory()->create();
    Tasks::factory(2)->create(['project_id' => $project->id]);
    Tasks::factory(5)->create();
    $response = $this->get('/api/tasks?project_id=' . $project->id);

    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->toHaveCount(2);
});

it('does not save a new task because of bad request', function () {
    $response = $this->post('/api/tasks', []);

    $response->assertStatus(400);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(false)
        ->data->toHaveCount(1);
});

it('does save a new task', function () {
    $task = Tasks::factory()->make();

    $response = $this->post('/api/tasks', $task->toArray());

    $response->assertStatus(201);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->name->toBe($task->name);
});

it('does not find a task', function () {
    $response = $this->get('/api/tasks/100');

    $response->assertStatus(404);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(false);
});

it('does find the task', function () {
    $task = Tasks::factory()->create();

    $response = $this->get('/api/tasks/' . $task->id);

    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->name->toBe($task->name);
});

it('does not pass validation of the tasks to update', function () {
    $task = Tasks::factory()->create();
    $response = $this->put('/api/tasks', []);
    $response->assertStatus(400);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(false)
        ->data->toHaveCount(1);
});

it('does update the task', function () {
    $task = Tasks::factory(5)->create();
    $response = $this->put('/api/tasks', [
        'tasks' => $task->toArray()
    ]);
    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->toHaveCount(5);
});

it('does not delete a not found task', function () {
    $response = $this->delete('/api/tasks/100');

    $response->assertStatus(404);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(false)
        ->data->toHaveCount(1);
});

it('does delete a task', function () {
    $task = Tasks::factory()->create();

    $response = $this->delete('/api/tasks/'.$task->id);

    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->name->toBe($task->name);
});
