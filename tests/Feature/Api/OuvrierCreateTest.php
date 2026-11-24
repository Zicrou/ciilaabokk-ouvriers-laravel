<?php

use App\Models\User;

test('authenticated API requests return form data from the worker create route', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/create');

    $response->assertOk()
        ->assertJsonStructure([
            'countries',
            'regions',
            'departements',
            'metiers',
            'domaines',
        ]);
});
