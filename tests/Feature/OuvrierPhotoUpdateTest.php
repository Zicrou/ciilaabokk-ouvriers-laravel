<?php

use App\Models\Countries;
use App\Models\Departement;
use App\Models\Diplome;
use App\Models\Domaine;
use App\Models\Metier;
use App\Models\Ouvrier;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

it('saves an updated worker photo path from the uploaded file', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $country = Countries::factory()->create();
    $region = Region::factory()->create(['country_id' => $country->id]);
    $departement = Departement::factory()->create(['region_id' => $region->id]);
    $domaine = Domaine::factory()->create();
    $metier = Metier::factory()->create(['domaine_id' => $domaine->id]);
    $diplome = Diplome::factory()->create();
    $ouvrier = Ouvrier::create([
        'name' => 'Awa Ndiaye',
        'country_id' => $country->id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'phone_number' => '7700000001',
        'address' => 'Dakar',
        'user_id' => $user->id,
    ]);

    DB::table('metier_ouvrier')->insert([
        'ouvrier_id' => $ouvrier->id,
        'metier_id' => $metier->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('domaine_ouvrier')->insert([
        'ouvrier_id' => $ouvrier->id,
        'domaine_id' => $domaine->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('diplome_ouvrier')->insert([
        'ouvrier_id' => $ouvrier->id,
        'diplome_id' => $diplome->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->actingAs($user)->put(route('ouvrier.update', $ouvrier), [
        'name' => 'Awa Ndiaye',
        'country_id' => $country->id,
        'region_id' => $region->id,
        'departement_id' => $departement->id,
        'date_of_birth' => '1990-01-01',
        'phone_number' => '7700000001',
        'address' => 'Dakar',
        'metiers' => [$metier->id],
        'domaines' => [$domaine->id],
        'diplomes' => [$diplome->id],
        'photo' => UploadedFile::fake()->image('profile.jpg'),
    ]);

    $response->assertRedirect(route('ouvrier.show', $ouvrier));

    $ouvrier->refresh();

    expect($ouvrier->photo)->toStartWith('ouvrier_profile/' . $user->id . '/');
    Storage::disk('public')->assertExists($ouvrier->photo);
});
