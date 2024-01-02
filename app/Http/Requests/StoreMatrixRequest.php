<?php

namespace App\Http\Requests;

use App\Helpers\ValidatesMatrices;
use Illuminate\Foundation\Http\FormRequest;

class StoreMatrixRequest extends FormRequest
{
    use ValidatesMatrices;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Inject the user_id server-side to avoid relying on client-side input.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
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
