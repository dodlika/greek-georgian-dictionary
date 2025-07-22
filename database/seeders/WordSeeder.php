<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Exception;

class WordSeeder extends Seeder
{
    public function run()
{
    try {
        if (!DB::getSchemaBuilder()->hasTable('words')) {
            throw new Exception('Words table does not exist');
        }

        $this->command->info('Clearing existing words from table...');
        DB::table('words')->truncate();  // Removes all rows and resets auto-increment

        $this->command->info('Starting to seed words from JSON...');

        $jsonPath = database_path('seeders/data/words.json');
        if (!file_exists($jsonPath)) {
            throw new Exception("JSON file not found at: $jsonPath");
        }

        @dd($jsonPath);

        $words = json_decode(file_get_contents($jsonPath), true);

        if (!is_array($words)) {
            throw new Exception("Invalid JSON structure in words.json");
        }

        DB::beginTransaction();

        $count = 0;
        foreach ($words as $wordData) {
            Word::create($wordData); 
            $count++;

            if ($count % 5 == 0) {
                $this->command->info("Processed {$count} words so far...");
            }
        }

        DB::commit();

        $this->command->info("Successfully seeded {$count} words from JSON!");
    } catch (Exception $e) {
        DB::rollBack();
        $this->command->error('Seeding failed: ' . $e->getMessage());
        throw $e;
    }
}

} 