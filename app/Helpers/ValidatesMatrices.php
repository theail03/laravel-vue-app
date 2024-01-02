<?php

namespace App\Helpers;

trait ValidatesMatrices
{
    /**
     * Common validation rules for matrices.
     *
     * @return array The validation rules.
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'rows' => 'required|integer|min:1|max:20',
            'columns' => 'required|integer|min:1|max:20',
            'user_id' => 'exists:users,id',
        ];
    }
}