<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create("id_ID");
        for ($i = 0; $i < 10; $i++) {
            Transaksi::create([
                'user_id' => $faker->numberBetween(1, 10),
                'pengirim' => $faker->name(),
                'penerima' => $faker->name(),
                'amount' => $faker->randomFloat(2, 10000, 1000000),
            ]);
        }
    }
}