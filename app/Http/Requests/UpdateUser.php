<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Astrotomic\Translatable\Validation\RuleFactory;

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
        $user_id = Auth::id();
        $rules = RuleFactory::make([
            'phone' => 'nullable|numeric|digits:10|unique:users,phone,'.$user_id,
            'birthdate' => 'nullable|date',
            '%about%'=> 'string|max:255',
            '%title%'=> 'string',
        ]);
        return $rules;
    }
}
