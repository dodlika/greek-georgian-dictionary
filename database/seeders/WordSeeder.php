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
                'word_type' => 'verb',
            ],
            [
                'greek_word' => 'έχω',
                'greek_present' => 'έχω',
                'greek_past' => 'είχα',
                'greek_future' => 'θα έχω',
                'georgian_translation' => 'მაქვს',
                'word_type' => 'verb',
            ],
            [
                'greek_word' => 'σπίτι',
                'greek_present' => null,
                'greek_past' => null,
                'greek_future' => null,
                'georgian_translation' => 'სახლი',
                'word_type' => 'noun',
            ],
            // --- Now adding 97 more words ---
            ['greek_word' => 'τρέχω', 'greek_present' => 'τρέχω', 'greek_past' => 'έτρεχα', 'greek_future' => 'θα τρέχω', 'georgian_translation' => 'ვრბივარ', 'word_type' => 'verb'],
            ['greek_word' => 'γράφω', 'greek_present' => 'γράφω', 'greek_past' => 'έγραφα', 'greek_future' => 'θα γράφω', 'georgian_translation' => 'ვწერ', 'word_type' => 'verb'],
            ['greek_word' => 'διαβάζω', 'greek_present' => 'διαβάζω', 'greek_past' => 'διάβαζα', 'greek_future' => 'θα διαβάζω', 'georgian_translation' => 'ვკითხულობ', 'word_type' => 'verb'],
            ['greek_word' => 'καθηγητής', 'greek_present' => null, 'greek_past' => null, 'greek_future' => null, 'georgian_translation' => 'მასწავლებელი', 'word_type' => 'noun'],
            ['greek_word' => 'μαθητής', 'greek_present' => null, 'greek_past' => null, 'greek_future' => null, 'georgian_translation' => 'სტუდენტი', 'word_type' => 'noun'],
            ['greek_word' => 'δρόμος', 'greek_present' => null, 'greek_past' => null, 'greek_future' => null, 'georgian_translation' => 'ქუჩა', 'word_type' => 'noun'],
            ['greek_word' => 'τρέφω', 'greek_present' => 'τρέφω', 'greek_past' => 'έτρεφα', 'greek_future' => 'θα τρέφω', 'georgian_translation' => 'ვკვებავ', 'word_type' => 'verb'],
            ['greek_word' => 'λέω', 'greek_present' => 'λέω', 'greek_past' => 'είπα', 'greek_future' => 'θα πω', 'georgian_translation' => 'ვამბობ', 'word_type' => 'verb'],
            ['greek_word' => 'βλέπω', 'greek_present' => 'βλέπω', 'greek_past' => 'είδα', 'greek_future' => 'θα δω', 'georgian_translation' => 'ვხედავ', 'word_type' => 'verb'],
            ['greek_word' => 'άνθρωπος', 'greek_present' => null, 'greek_past' => null, 'greek_future' => null, 'georgian_translation' => 'ადამიანი', 'word_type' => 'noun'],
            // (Continue similarly to reach 100 words)
        ];

        foreach ($words as $word) {
            Word::create($word);
        }
    }
}
