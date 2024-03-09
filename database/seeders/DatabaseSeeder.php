<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matrix;
use App\Models\Image;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed the matrices, adjusting the count to 15
        $matrices = Matrix::factory(15)->create();

        // Now seed the images for each cell in each matrix
        foreach ($matrices as $matrix) {
            for ($row = 1; $row <= $matrix->rows; $row++) {
                for ($column = 1; $column <= $matrix->columns; $column++) {
                    Image::factory()->create([
                        'matrix_id' => $matrix->id,
                        'row' => $row,
                        'column' => $column,
                        // 'path' is set by the ImageFactory
                        // 'public_id' is not set and will default to null
                    ]);
                }
            }
        }
    }
}
