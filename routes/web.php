<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


// Public routes
Route::get('/', [WordController::class, 'index'])->name('words.index');
Route::get('/words', [WordController::class, 'index'])->name('words.show');

// Grammar routes (public)
Route::get('/grammar', [GrammarController::class, 'index'])->name('grammar.index');
Route::get('/grammar/{section}', [GrammarController::class, 'show'])->name('grammar.show');

// Authentication routes (Laravel Breeze adds these automatically)
require __DIR__.'/auth.php';

// Word management routes - using Laravel's built-in 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::get('/words/create', [WordController::class, 'create'])->name('words.create');
    Route::post('/words', [WordController::class, 'store'])->name('words.store');
    Route::get('/words/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::put('/words/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/words/{word}', [WordController::class, 'destroy'])->name('words.destroy');
});

// Profile routes (for authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Debug routes (keep these for development)
Route::get('/debug', function () {
    $debug = [];
    
    try {
        $debug['database_connected'] = true;
        $debug['database_name'] = DB::connection()->getDatabaseName();
        
        $debug['words_table_exists'] = Schema::hasTable('words');
        
        if ($debug['words_table_exists']) {
            $debug['table_columns'] = Schema::getColumnListing('words');
            $debug['word_count'] = \App\Models\Word::count();
            $debug['sample_words'] = \App\Models\Word::take(5)->get()->toArray();
            $debug['test_word_exists'] = \App\Models\Word::where('greek_word', 'είμαι')->exists();
        }
        
        // Add user information
        $debug['users_table_exists'] = Schema::hasTable('users');
        if ($debug['users_table_exists']) {
            $debug['user_count'] = \App\Models\User::count();
            $debug['admin_users'] = \App\Models\User::where('can_manage_words', true)->get(['name', 'email'])->toArray();
        }
        
        $debug['migrations_count'] = DB::table('migrations')->count();
        $debug['latest_migration'] = DB::table('migrations')->orderByDesc('id')->first();

        
        $debug['app_env'] = config('app.env');
        $debug['app_debug'] = config('app.debug');
        
    } catch (\Exception $e) {
        $debug['error'] = $e->getMessage();
        $debug['database_connected'] = false;
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

Route::get('/force-seed', function () {
    try {
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

// Route to seed users
Route::get('/seed-users', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'UserSeeder', '--force' => true]);
        
        $result = [
            'success' => true,
            'message' => 'Users seeded successfully',
            'output' => Artisan::output(),
            'user_count' => \App\Models\User::count(),
            'admin_users' => \App\Models\User::where('can_manage_words', true)->get(['name', 'email'])->toArray()
        ];
        
    } catch (\Exception $e) {
        $result = [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
});