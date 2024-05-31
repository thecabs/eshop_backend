<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Factory as Faker;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();
        return [
            'codePro' => $faker->unique()->randomNumber(6, true),
            'nomPro' => $faker->word(),
            'prix' => $faker->randomFloat(0, 0, 999999),
            'qte' => $faker->randomNumber(2, true),
            'description' => str::limit($faker->paragraph(2), 60),
            'codeArrivage' => $faker->word(),

            'actif' => $faker->boolean,
            'prixAchat' => $faker->randomFloat(0, 0, 99999999),
            'pourcentage' => $faker->randomFloat(2, 0, 99),
            'promo' => $faker->boolean,
        ];
    }
}
