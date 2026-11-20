<?php

use App\Models\Departement;
use App\Models\Ouvrier;
use App\Models\Region;
use App\Models\User;

it('finds public worker profiles by name or phone number', function (string $search) {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);

    Ouvrier::create([
        'name' => 'Awa Ndiaye',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'phone_number' => '77 123 45 67',
        'address' => 'Dakar',
    ]);

    $response = $this->get(route('ouvrier.rechercher', ['search' => $search]));

    $response->assertOk();
    $response->assertSee('Awa Ndiaye');
})->with([
    'name' => 'Awa',
    'phone number' => '77 123',
]);

it('renders a public worker profile with contact information', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $ouvrier = Ouvrier::create([
        'name' => 'Awa Ndiaye',
        'region_id' => $region->id,
        'country_id' => $region->country_id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'phone_number' => '77 123 45 67',
        'address' => 'Dakar',
    ]);

    $response = $this->get(route('ouvrier.show', $ouvrier));

    $response->assertOk();
    $response->assertSee('Awa Ndiaye');
    $response->assertSee('77 123 45 67');
});
