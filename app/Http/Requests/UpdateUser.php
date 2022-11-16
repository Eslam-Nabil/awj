<?php

namespace App\Http\Requests;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = RuleFactory::make([
            'phone' => 'nullable|numeric|digits:10|unique:users',
            'birthdate' => 'nullable|date',
            '%about%'=> 'string|max:255',
            '%title%'=> 'string',
        ]);
        return $rules;
    }
}
