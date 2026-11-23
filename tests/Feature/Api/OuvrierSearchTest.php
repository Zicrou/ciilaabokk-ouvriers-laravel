<?php

use App\Models\Departement;
use App\Models\Domaine;
use App\Models\Ouvrier;
use App\Models\Region;
use App\Models\User;

test('authenticated API requests reach the worker search endpoint', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/rechercher');

    $response->assertOk()
        ->assertJsonStructure(['ouvriers']);
});

test('API worker search filters results by name or phone number', function (string $search) {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);

    Ouvrier::create([
        'nom' => 'Awa Ndiaye',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 123 45 67',
        'address' => 'Dakar',
    ]);

    Ouvrier::create([
        'nom' => 'Moussa Diop',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '76 555 55 55',
        'address' => 'Dakar',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/rechercher?search='.urlencode($search));

    $response->assertOk()
        ->assertJsonCount(1, 'ouvriers')
        ->assertJsonPath('ouvriers.0.nom', 'Awa Ndiaye');
})->with([
    'name' => 'Awa',
    'phone number' => '77 123',
]);

test('API worker search filters results by domain', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $matchingDomain = Domaine::factory()->create();
    $otherDomain = Domaine::factory()->create();

    $matchingWorker = Ouvrier::create([
        'nom' => 'Ouvrier domaine cible',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 100 00 01',
        'address' => 'Dakar',
    ]);
    $otherWorker = Ouvrier::create([
        'nom' => 'Ouvrier autre domaine',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 100 00 02',
        'address' => 'Dakar',
    ]);
    $matchingWorker->domaines()->attach($matchingDomain);
    $otherWorker->domaines()->attach($otherDomain);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/rechercher?domaine_id='.$matchingDomain->id);

    $response->assertOk()
        ->assertJsonCount(1, 'ouvriers')
        ->assertJsonPath('ouvriers.0.nom', 'Ouvrier domaine cible');
});

test('API worker search filters results by region', function () {
    $user = User::factory()->create();
    $matchingRegion = Region::factory()->create();
    $otherRegion = Region::factory()->create();
    $matchingDepartment = Departement::factory()->create(['region_id' => $matchingRegion->id]);
    $otherDepartment = Departement::factory()->create(['region_id' => $otherRegion->id]);

    Ouvrier::create([
        'nom' => 'Ouvrier région cible',
        'region_id' => $matchingRegion->id,
        'country_id' => $matchingRegion->country_id,
        'departement_id' => $matchingDepartment->id,
        'user_id' => $user->id,
        'telephone' => '77 200 00 01',
        'address' => 'Dakar',
    ]);
    Ouvrier::create([
        'nom' => 'Ouvrier autre région',
        'region_id' => $otherRegion->id,
        'country_id' => $otherRegion->country_id,
        'departement_id' => $otherDepartment->id,
        'user_id' => $user->id,
        'telephone' => '77 200 00 02',
        'address' => 'Dakar',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/rechercher?region_id='.$matchingRegion->id);

    $response->assertOk()
        ->assertJsonCount(1, 'ouvriers')
        ->assertJsonPath('ouvriers.0.nom', 'Ouvrier région cible');
});
