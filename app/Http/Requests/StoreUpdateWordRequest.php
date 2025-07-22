<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateWordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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

}
