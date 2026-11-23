<?php

use App\Models\Departement;
use App\Models\Domaine;
use App\Models\Metier;
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

test('API location search starts at 20 km and applies the domain and region filters', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $otherRegion = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $otherDepartement = Departement::factory()->create(['region_id' => $otherRegion->id]);
    $domain = Domaine::factory()->create();
    $otherDomain = Domaine::factory()->create();
    $unlocatedWorker = Ouvrier::create([
        'nom' => 'Ouvrier sans coordonnées',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 300 00 04',
        'address' => 'Dakar',
    ]);
    $unlocatedWorker->domaines()->attach($domain);

    $nearbyWorker = Ouvrier::create([
        'nom' => 'Ouvrier proche',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 300 00 01',
        'address' => 'Dakar',
        'latitude' => 14.7667,
        'longitude' => -17.4677,
    ]);
    $nearbyWorker->domaines()->attach($domain);

    $wrongDomainWorker = Ouvrier::create([
        'nom' => 'Ouvrier autre domaine',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 300 00 02',
        'address' => 'Dakar',
        'latitude' => 14.7667,
        'longitude' => -17.4677,
    ]);
    $wrongDomainWorker->domaines()->attach($otherDomain);

    Ouvrier::create([
        'nom' => 'Ouvrier autre région',
        'region_id' => $otherRegion->id,
        'country_id' => $otherRegion->country_id,
        'departement_id' => $otherDepartement->id,
        'user_id' => $user->id,
        'telephone' => '77 300 00 03',
        'address' => 'Dakar',
        'latitude' => 14.7667,
        'longitude' => -17.4677,
    ])->domaines()->attach($domain);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ouvrier/rechercher?'.http_build_query([
        'latitude' => 14.7167,
        'longitude' => -17.4677,
        'domaine_id' => $domain->id,
        'region_id' => $region->id,
    ]));

    $response->assertOk()
        ->assertJsonCount(1, 'ouvriers')
        ->assertJsonPath('ouvriers.0.nom', 'Ouvrier proche')
        ->assertJsonPath('ouvriers.0.distance_km', 5.56)
        ->assertJsonPath('search_radius_km', 20);
    $this->assertArrayNotHasKey('latitude', $response->json('ouvriers.0'));
    $this->assertArrayNotHasKey('longitude', $response->json('ouvriers.0'));
});

test('API location search expands by 20 km until it finds workers', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domain = Domaine::factory()->create();
    $otherDomain = Domaine::factory()->create();

    $nearbyOtherDomainWorker = Ouvrier::create([
        'nom' => 'Ouvrier proche autre domaine',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 400 00 03',
        'address' => 'Dakar',
        'latitude' => 14.7167,
        'longitude' => -17.4177,
    ]);
    $nearbyOtherDomainWorker->domaines()->attach($otherDomain);

    $matchingWorker = Ouvrier::create([
        'nom' => 'Ouvrier à environ 33 km',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 400 00 01',
        'address' => 'Dakar',
        'latitude' => 14.7167,
        'longitude' => -17.1677,
    ]);
    $matchingWorker->domaines()->attach($domain);

    Ouvrier::create([
        'nom' => 'Ouvrier à environ 55 km',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 400 00 02',
        'address' => 'Dakar',
        'latitude' => 14.7167,
        'longitude' => -16.9677,
    ])->domaines()->attach($domain);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ouvrier/rechercher?'.http_build_query([
        'latitude' => 14.7167,
        'longitude' => -17.4677,
        'domaine_id' => $domain->id,
    ]));

    $response->assertOk()
        ->assertJsonCount(1, 'ouvriers')
        ->assertJsonPath('ouvriers.0.nom', 'Ouvrier à environ 33 km')
        ->assertJsonPath('search_radius_km', 40);
});

test('API location search stops at 100 km when there are no workers nearby', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);

    Ouvrier::create([
        'nom' => 'Ouvrier trop éloigné',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 500 00 01',
        'address' => 'Dakar',
        'latitude' => 14.7167,
        'longitude' => -15.9677,
    ]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ouvrier/rechercher?'.http_build_query([
        'latitude' => 14.7167,
        'longitude' => -17.4677,
    ]));

    $response->assertOk()
        ->assertJsonCount(0, 'ouvriers')
        ->assertJsonPath('search_radius_km', 100);
});

test('API location search validates coordinate ranges and requires both coordinates', function (array $parameters, string $errorField) {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/ouvrier/rechercher?'.http_build_query($parameters));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($errorField);
})->with([
    'latitude outside valid range' => [['latitude' => 91, 'longitude' => 0], 'latitude'],
    'missing longitude' => [['latitude' => 14.7], 'longitude'],
]);

test('API worker creation stores location coordinates', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domain = Domaine::factory()->create();
    $metier = Metier::factory()->create(['domaine_id' => $domain->id]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/ouvrier', [
        'nom' => 'Ouvrier avec GPS',
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'metiers' => [$metier->id],
        'domaines' => [$domain->id],
        'telephone' => '77 600 00 01',
        'address' => 'Dakar',
        'latitude' => 14.7167,
        'longitude' => -17.4677,
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('ouvriers', [
        'nom' => 'Ouvrier avec GPS',
        'latitude' => 14.7167,
        'longitude' => -17.4677,
    ]);
});

test('API worker updates store location coordinates', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domain = Domaine::factory()->create();
    $metier = Metier::factory()->create(['domaine_id' => $domain->id]);
    $worker = Ouvrier::create([
        'nom' => 'Ouvrier à déplacer',
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '77 700 00 01',
        'address' => 'Dakar',
        'latitude' => 14.7,
        'longitude' => -17.4,
    ]);

    $response = $this->actingAs($user, 'sanctum')->putJson('/api/v1/ouvrier/'.$worker->id, [
        'nom' => $worker->nom,
        'country_id' => $region->country_id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'metiers' => [$metier->id],
        'domaines' => [$domain->id],
        'telephone' => $worker->telephone,
        'address' => $worker->address,
        'latitude' => 14.8,
        'longitude' => -17.5,
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('ouvriers', [
        'id' => $worker->id,
        'latitude' => 14.8,
        'longitude' => -17.5,
    ]);
});
