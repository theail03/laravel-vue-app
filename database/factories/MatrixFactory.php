<?php

namespace Database\Factories;

use App\Models\Matrix;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MatrixFactory extends Factory
{
    protected $model = Matrix::class;

    public function definition()
    {
        return [
            // 'user_id' is not set and will default to null
            'title' => $this->faker->sentence,
            'rows' => $this->faker->numberBetween(1, 10),
            'columns' => $this->faker->numberBetween(1, 10),
            'is_public' => true,
            'slug' => function (array $matrix) {
                return Str::slug($matrix['title']);
            },
        ];
    }
}
