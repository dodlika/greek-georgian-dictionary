<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Word;

class WordSeeder extends Seeder
{
    public function run()
    {
        $words = [
            [
                'greek_word' => 'είμαι',
                'greek_present' => 'είμαι',
                'greek_past' => 'ήμουν',
                'greek_future' => 'θα είμαι',
                'georgian_translation' => 'ვარ',
                'word_type' => 'verb'
            ],
            [
                'greek_word' => 'έχω',
                'greek_present' => 'έχω',
                'greek_past' => 'είχα',
                'greek_future' => 'θα έχω',
                'georgian_translation' => 'მაქვს',
                'word_type' => 'verb'
            ],
            [
                'greek_word' => 'σπίτι',
                'greek_present' => null,
                'greek_past' => null,
                'greek_future' => null,
                'georgian_translation' => 'სახლი',
                'word_type' => 'noun'
            ]
        ];

        foreach ($words as $word) {
            Word::create($word);
        }
    }
}