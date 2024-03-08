<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed the matrices first
        $matrices = \App\Models\Matrix::factory(50)->create();

        // Now seed the images
        $matrices->each(function ($matrix) {
            \App\Models\Image::factory(rand(5, 15))->create([
                'matrix_id' => $matrix->id
            ]);
        });
    }
}
