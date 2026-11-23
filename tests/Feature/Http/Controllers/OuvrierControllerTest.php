<?php

use App\Models\Departement;
use App\Models\Diplome;
use App\Models\Domaine;
use App\Models\Metier;
use App\Models\Ouvrier;
use App\Models\Region;
use App\Models\User;

it('creates an ouvrièr and attaches the submitted relationships', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domaine = Domaine::factory()->create();
    $metier = Metier::factory()->create(['domaine_id' => $domaine->id]);

    $response = $this->actingAs($user)->post(route('ouvrier.store'), [
        'nom' => 'Nouvel ouvrier',
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'domaines' => [$domaine->id],
        'metiers' => [$metier->id],
        'telephone' => '770000001',
        'address' => 'Dakar',
        'user_id' => $otherUser->id,
    ]);

    $response->assertRedirect(route('ouvrier.index'));

    $ouvrier = Ouvrier::query()->where('telephone', '770000001')->firstOrFail();

    $this->assertModelExists($ouvrier);
    expect($ouvrier->user_id)->toBe($user->id);
    expect($ouvrier->domaines()->whereKey($domaine->id)->exists())->toBeTrue();
    expect($ouvrier->metiers()->whereKey($metier->id)->exists())->toBeTrue();
});

it('rejects a phone number that is already assigned to an ouvrièr', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domaine = Domaine::factory()->create();
    $metier = Metier::factory()->create(['domaine_id' => $domaine->id]);

    Ouvrier::create([
        'nom' => 'Ouvrier existant',
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'user_id' => $user->id,
        'telephone' => '770000000',
        'address' => 'Dakar',
    ]);

    $response = $this->actingAs($user)
        ->from(route('ouvrier.create'))
        ->post(route('ouvrier.store'), [
            'nom' => 'Nouvel ouvrier',
            'region_id' => $region->id,
            'departement_id' => $departement->id,
            'domaines' => [$domaine->id],
            'metiers' => [$metier->id],
            'telephone' => '770000000',
            'address' => 'Dakar',
        ]);

    $response->assertRedirect(route('ouvrier.create'));
    $response->assertSessionHasErrors('telephone');
});
