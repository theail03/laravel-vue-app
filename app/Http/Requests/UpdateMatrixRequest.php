<?php

namespace App\Http\Requests;

use App\Helpers\ValidatesMatrices;
use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMatrixRequest extends FormRequest
{
    use ValidatesMatrices;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $matrix = $this->route('matrix');
        return UserHelper::verifyUser($matrix->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->matrixRules();
    }
}
