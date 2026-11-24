<?php

use App\Models\Departement;
use App\Models\Ouvrier;
use App\Models\Region;
use App\Models\User;
use App\Services\FirebaseOuvrierLocationWriter;
use Mockery\MockInterface;

test('authenticated users can update the location of their own worker in Firebase', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $worker = Ouvrier::create([
        'nom' => 'Ouvrier connecté',
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 800 00 01',
        'address' => 'Dakar',
    ]);
    $otherWorker = Ouvrier::create([
        'nom' => 'Autre ouvrier',
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'user_id' => $otherUser->id,
        'telephone' => '77 800 00 02',
        'address' => 'Dakar',
    ]);

    $this->mock(FirebaseOuvrierLocationWriter::class, function (MockInterface $mock) use ($worker) {
        $mock->shouldReceive('write')
            ->once()
            ->withArgs(fn (Ouvrier $ouvrier, float $latitude, float $longitude) => $ouvrier->is($worker)
                && $latitude === 14.7167
                && $longitude === -17.4677)
            ->andReturn('2026-11-24T02:00:00+00:00');
    });

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/ouvrier/location', [
            'latitude' => 14.7167,
            'longitude' => -17.4677,
            'ouvrier_id' => $otherWorker->id,
        ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('updated_at', '2026-11-24T02:00:00+00:00');
});

test('location updates require authentication and a worker profile', function () {
    $this->mock(FirebaseOuvrierLocationWriter::class);

    $this->postJson('/api/v1/ouvrier/location', [
        'latitude' => 14.7,
        'longitude' => -17.4,
    ])->assertUnauthorized();

    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/ouvrier/location', [
            'latitude' => 14.7,
            'longitude' => -17.4,
        ])->assertNotFound();
});

test('location updates validate coordinate bounds', function () {
    $user = User::factory()->create();

    $this->mock(FirebaseOuvrierLocationWriter::class);

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/ouvrier/location', [
            'latitude' => 91,
            'longitude' => -17.4,
        ])->assertJsonValidationErrors('latitude');
});

test('authenticated users can read their own location history incrementally', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $worker = Ouvrier::create([
        'nom' => 'Ouvrier suivi',
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 800 00 03',
        'address' => 'Dakar',
    ]);

    $this->mock(FirebaseOuvrierLocationWriter::class, function (MockInterface $mock) use ($worker) {
        $mock->shouldReceive('read')
            ->once()
            ->withArgs(fn (Ouvrier $ouvrier, ?int $since) => $ouvrier->is($worker) && $since === 1795480000000)
            ->andReturn([
                'position' => [
                    'latitude' => 14.7167,
                    'longitude' => -17.4677,
                    'updated_at_ms' => 1795480020000,
                ],
                'positions' => [
                    [
                        'id' => '-Oabc123',
                        'latitude' => 14.7167,
                        'longitude' => -17.4677,
                        'recorded_at_ms' => 1795480020000,
                    ],
                ],
            ]);
    });

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/location?since=1795480000000')
        ->assertOk()
        ->assertJsonPath('positions.0.id', '-Oabc123')
        ->assertJsonPath('position.latitude', 14.7167);
});
