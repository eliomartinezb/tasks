<?php

use App\Models\Project;

it('does not load any projects', function () {
    $response = $this->get('/api/projects');

    $response->assertStatus(404);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(2)
        ->success->toBe(false);
});

it('does load projects', function () {
    Project::factory(5)->create();
    $response = $this->get('/api/projects');

    $response->assertStatus(200);

    expect(json_encode($response->json(), true))
        ->json()
        ->toHaveCount(3)
        ->success->toBe(true)
        ->data->toHaveCount(5);
});
