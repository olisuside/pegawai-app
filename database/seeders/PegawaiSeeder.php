<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posisi = ['manager', 'staff', 'magang'];
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            DB::table('pegawais')->insert([
                'nama' => $faker->name,
                'posisi' => $posisi[array_rand($posisi)],
                'tanggal_masuk' => $faker->dateTimeBetween('-24 years', 'now')->format('Y-m-d'),
                'foto' => null, // bisa diganti dengan path file foto jika ada
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
