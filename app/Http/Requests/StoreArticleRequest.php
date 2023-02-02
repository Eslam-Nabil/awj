<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Astrotomic\Translatable\Validation\RuleFactory;

class StoreArticleRequest extends FormRequest
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
            '%title%'              => 'required|string',
            '%description%'        => 'required|string',
            'category_id'          => 'required|integer',
            'article_file_path'    => 'required|mimes:pdf',
            'audio_file_path'      => 'required|mimes:audio/mpeg,ogg,wav,mp3',
            'cover_file_path'      => 'required|mimes:jpg,jpeg,gif,webp,png',
            'price'                => 'required|integer',
            'pages_count'          => 'required|integer',
            'task.*.file_path'     => 'required|mimes:pdf',
            'task.*.%description%' => 'required|string',
            'task.*.%title%'       => 'required|string',
            'task.*.duration'      => 'required|string',
        ]);
        return $rules;
    }
}
