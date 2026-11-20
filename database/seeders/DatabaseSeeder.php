<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domaine;
use App\Models\Metier;
use App\Models\Departement;
use App\Models\Region;
use App\Models\Entreprise;
use App\Models\Diplome;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Hash;
use App\Models\Countries;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => "User 1",
            'email' => "user1@gmail.com",
            'phone_number' => '0000000000',
            'password' => Hash::make("Password123!")
        ]);

        $country1 = Countries::factory()->create([
            'name' => "Country 1"
        ]);

        $country2 = Countries::factory()->create([
            'name' => "Country 2"
        ]);

        $region1 = Region::factory()->create([
            'name' => 'Region 1',
            'country_id' => $country1->id,
        ]);
        $region2 = Region::factory()->create([
            'name' => 'Region 2',
            'country_id' => $country2->id,
        ]);
        $departement1 = Departement::factory()->create([
            'name' => 'Département 1',
            'region_id' => $region1->id,
        ]);
        $departement2 = Departement::factory()->create([
            'name' => 'Département 1',
            'region_id' => $region2->id,
        ]);
        $domaine1 = Domaine::factory()->create([
            'name' => 'Domaine 1',
        ]);
        $domaine2 = Domaine::factory()->create([
            'name' => 'Domaine 2',
        ]);
        Metier::factory()->create([
            'name' => 'Métier 1',
            'domaine_id' => $domaine1->id
        ]);
        Metier::factory()->create([
            'name' => 'Métier 2',
            'domaine_id' => $domaine2->id,
        ]);

        Diplome::factory()->create([
            'name' => 'diplôme 1',
        ]);
        Diplome::factory()->create([
            'name' => 'diplôme 2',
        ]);
    }
}
