<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Word;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        $words = [
            ['greek_word' => 'καλημέρα', 'georgian_translation' => 'დილა მშვიდობისა', 'pronunciation' => 'kalimera'],
            ['greek_word' => 'καλησπέρα', 'georgian_translation' => 'საღამო მშვიდობისა', 'pronunciation' => 'kalispera'],
            ['greek_word' => 'γεια σας', 'georgian_translation' => 'გამარჯობა', 'pronunciation' => 'yia sas'],
            ['greek_word' => 'αντίο', 'georgian_translation' => 'ნახვამდის', 'pronunciation' => 'adio'],
            ['greek_word' => 'ευχαριστώ', 'georgian_translation' => 'მადლობა', 'pronunciation' => 'efcharisto'],
            ['greek_word' => 'παρακαλώ', 'georgian_translation' => 'თუ შეიძლება', 'pronunciation' => 'parakalo'],
            ['greek_word' => 'συγγνώμη', 'georgian_translation' => 'უკაცრავად', 'pronunciation' => 'signomi'],
            ['greek_word' => 'ναι', 'georgian_translation' => 'დიახ', 'pronunciation' => 'ne'],
            ['greek_word' => 'όχι', 'georgian_translation' => 'არა', 'pronunciation' => 'ochi'],
            ['greek_word' => 'νερό', 'georgian_translation' => 'წყალი', 'pronunciation' => 'nero'],
            ['greek_word' => 'φαγητό', 'georgian_translation' => 'საჭმელი', 'pronunciation' => 'fagito'],
            ['greek_word' => 'σπίτι', 'georgian_translation' => 'სახლი', 'pronunciation' => 'spiti'],
            ['greek_word' => 'δρόμος', 'georgian_translation' => 'ქუჩა', 'pronunciation' => 'dromos'],
            ['greek_word' => 'αυτοκίνητο', 'georgian_translation' => 'მანქანა', 'pronunciation' => 'aftokinito'],
            ['greek_word' => 'λεωφορείο', 'georgian_translation' => 'ავტობუსი', 'pronunciation' => 'leoforeio'],
        ];

        foreach ($words as $wordData) {
            // Only create if this Greek word doesn't already exist
            Word::firstOrCreate(
                ['greek_word' => $wordData['greek_word']], // Search criteria
                $wordData // Data to insert if not found
            );
        }

        $this->command->info('Word seeding completed. Preserved existing user words.');
    }
}