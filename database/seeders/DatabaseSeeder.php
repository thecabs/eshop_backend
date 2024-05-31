<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\categorie;
use App\Models\photo;
use App\Models\produit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        categorie::factory(5)->create();
        $categories = categorie::all();

        produit::factory(10)
            ->sequence(fn() => [
                'categorie_id' => $categories->random(),
            ])->create();

        /*$produits = produit::all();
        photo::factory(20)
            ->sequence(fn() => [
                'produit_codePro' => $produits->random(),
            ])->create();*/
    }
}
