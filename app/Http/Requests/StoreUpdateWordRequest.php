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

    public function rules()
    {
        $rules = [
            'greek_word' => 'required|string|max:255',
            'georgian_translation' => 'required|string|max:255',
            'word_type' => 'required|in:verb,noun,adjective,adverb',
        ];

        // If it's a verb, validate the complex structure
        if ($this->word_type === 'verb') {
            $rules['english_translation'] = 'nullable|string|max:255';
            
            // Validate present tense
            $rules['present_tense.1st_singular.greek'] = 'nullable|string|max:255';
            $rules['present_tense.1st_singular.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.1st_singular.english'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_singular.greek'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_singular.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_singular.english'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_singular.greek'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_singular.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_singular.english'] = 'nullable|string|max:255';
            $rules['present_tense.1st_plural.greek'] = 'nullable|string|max:255';
            $rules['present_tense.1st_plural.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.1st_plural.english'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_plural.greek'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_plural.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.2nd_plural.english'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_plural.greek'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_plural.georgian'] = 'nullable|string|max:255';
            $rules['present_tense.3rd_plural.english'] = 'nullable|string|max:255';

            // Validate past tense
            $rules['past_tense.1st_singular.greek'] = 'nullable|string|max:255';
            $rules['past_tense.1st_singular.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.1st_singular.english'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_singular.greek'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_singular.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_singular.english'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_singular.greek'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_singular.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_singular.english'] = 'nullable|string|max:255';
            $rules['past_tense.1st_plural.greek'] = 'nullable|string|max:255';
            $rules['past_tense.1st_plural.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.1st_plural.english'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_plural.greek'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_plural.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.2nd_plural.english'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_plural.greek'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_plural.georgian'] = 'nullable|string|max:255';
            $rules['past_tense.3rd_plural.english'] = 'nullable|string|max:255';

            // Validate future tense
            $rules['future_tense.1st_singular.greek'] = 'nullable|string|max:255';
            $rules['future_tense.1st_singular.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.1st_singular.english'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_singular.greek'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_singular.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_singular.english'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_singular.greek'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_singular.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_singular.english'] = 'nullable|string|max:255';
            $rules['future_tense.1st_plural.greek'] = 'nullable|string|max:255';
            $rules['future_tense.1st_plural.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.1st_plural.english'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_plural.greek'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_plural.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.2nd_plural.english'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_plural.greek'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_plural.georgian'] = 'nullable|string|max:255';
            $rules['future_tense.3rd_plural.english'] = 'nullable|string|max:255';
        } else {
            // For non-verbs, validate the simple structure
            $rules['greek_present'] = 'nullable|string|max:255';
            $rules['greek_past'] = 'nullable|string|max:255';
            $rules['greek_future'] = 'nullable|string|max:255';
        }

        return $rules;
    }
    public function messages()
{
    return [
        'greek_word.unique' => 'This Greek word already exists.',
    ];
}
}
