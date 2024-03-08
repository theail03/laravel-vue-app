<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        return [
            // 'matrix_id' will be passed externally
            'row' => $this->faker->numberBetween(1, 10), // default value
            'column' => $this->faker->numberBetween(1, 10), // default value
            'path' => $this->faker->imageUrl(),
            'public_id' => $this->faker->uuid,
        ];
    }

    /**
     * Adjust the row and column numbers to not exceed the matrix dimensions.
     */
    public function configure()
    {
        return $this->afterMaking(function (Image $image) {
            if ($image->matrix_id) {
                $matrix = \App\Models\Matrix::find($image->matrix_id);
                $image->row = $this->faker->numberBetween(1, $matrix->rows);
                $image->column = $this->faker->numberBetween(1, $matrix->columns);
            }
        });
    }
}
