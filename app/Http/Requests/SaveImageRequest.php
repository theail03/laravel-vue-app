<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You might want to do some checks to see if the user is authorized
        // to save an image. For now, let's just return true.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'matrixId' => 'required|exists:matrices,id', 
            'row' => 'required|integer',
            'column' => 'required|integer',
            'image' => 'required|string', // This is a base64 string
        ];
    }
}
