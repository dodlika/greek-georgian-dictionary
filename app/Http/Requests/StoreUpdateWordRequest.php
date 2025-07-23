<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUpdateWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Using Auth facade explicitly
        return Auth::check() && Auth::user()->can_manage_words;
    }

    public function rules(): array
    {
        return [
            'greek_word' => 'required|string|max:255',
            'greek_present' => 'nullable|string|max:255',
            'greek_past' => 'nullable|string|max:255',
            'greek_future' => 'nullable|string|max:255',
            'georgian_translation' => 'required|string|max:255',
            'word_type' => 'required|string|max:255',
        ];
    }
    public function messages()
{
    return [
        'greek_word.unique' => 'This Greek word already exists.',
    ];
}
}
