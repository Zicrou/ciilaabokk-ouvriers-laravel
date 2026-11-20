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
            'nom' => "User 1",
            'email' => "user1@gmail.com",
            'telephone' => '0000000000',
            'password' => Hash::make("Password123!"),
            'role' => 'admin'
        ]);

        $country1 = Countries::factory()->create([
            'nom' => "Country 1"
        ]);

        $country2 = Countries::factory()->create([
            'nom' => "Country 2"
        ]);

        $region1 = Region::factory()->create([
            'nom' => 'Region 1',
            'country_id' => $country1->id,
        ]);
        $region2 = Region::factory()->create([
            'nom' => 'Region 2',
            'country_id' => $country2->id,
        ]);
        $departement1 = Departement::factory()->create([
            'nom' => 'Département 1',
            'region_id' => $region1->id,
        ]);
        $departement2 = Departement::factory()->create([
            'nom' => 'Département 1',
            'region_id' => $region2->id,
        ]);
        $domaine1 = Domaine::factory()->create([
            'nom' => 'Domaine 1',
        ]);
        $domaine2 = Domaine::factory()->create([
            'nom' => 'Domaine 2',
        ]);
        Metier::factory()->create([
            'nom' => 'Métier 1',
            'domaine_id' => $domaine1->id
        ]);
        Metier::factory()->create([
            'nom' => 'Métier 2',
            'domaine_id' => $domaine2->id,
        ]);

        
    }
}
