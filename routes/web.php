<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [WordController::class, 'index'])->name('words.index');
Route::get('/words/create', [WordController::class, 'create'])->name('words.create');
Route::post('/words', [WordController::class, 'store'])->name('words.store');
Route::get('/debug', function () {
    $debug = [];
    
    try {
        // Test database connection
        $debug['database_connected'] = true;
        $debug['database_name'] = DB::connection()->getDatabaseName();
        
        // Check if words table exists
        $debug['words_table_exists'] = Schema::hasTable('words');
        
        if ($debug['words_table_exists']) {
            // Get table structure
            $debug['table_columns'] = Schema::getColumnListing('words');
            
            // Count words
            $debug['word_count'] = \App\Models\Word::count();
            
            // Get first few words
            $debug['sample_words'] = \App\Models\Word::take(5)->get()->toArray();
            
            // Check if specific words exist
            $debug['test_word_exists'] = \App\Models\Word::where('greek_word', 'είμαι')->exists();
        }
        
        // Check migrations
        $debug['migrations_count'] = DB::table('migrations')->count();
        $debug['latest_migration'] = DB::table('migrations')->latest()->first();
        
        // Environment info
        $debug['app_env'] = config('app.env');
        $debug['app_debug'] = config('app.debug');
        
    } catch (\Exception $e) {
        $debug['error'] = $e->getMessage();
        $debug['database_connected'] = false;
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Also add a route to manually trigger seeding
Route::get('/force-seed', function () {
    try {
        // Clear any existing data (optional)
        // \App\Models\Word::truncate();
        
        // Run the seeder
        Artisan::call('db:seed', ['--class' => 'WordSeeder', '--force' => true]);
        
        $result = [
            'success' => true,
            'message' => 'Seeding completed',
            'output' => Artisan::output(),
            'word_count_after' => \App\Models\Word::count()
        ];
        
    } catch (\Exception $e) {
        $result = [
            'success' => false,
            'error' => $e->getMessage(),
            'word_count' => \App\Models\Word::count()
        ];
    }
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
});