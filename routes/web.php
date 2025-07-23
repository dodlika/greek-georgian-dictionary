<?php

use App\Http\Controllers\QuizController;
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

    // Add this route to your web.php for emergency cache clearing on production
Route::get('/clear-all-cache', function() {
    try {
        // Clear all Laravel caches
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        
        // Try to clear compiled classes
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        return response()->json([
            'message' => 'All caches cleared successfully',
            'route_clear' => Artisan::output(),
            'timestamp' => now()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to clear cache: ' . $e->getMessage(),
            'timestamp' => now()
        ], 500);
    }
});

// Debug route to check current routes
Route::get('/debug-routes', function() {
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        $routes[] = [
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    }
    
    return response()->json([
        'total_routes' => count($routes),
        'quiz_routes' => array_filter($routes, function($route) {
            return str_contains($route['uri'], 'quiz');
        }),
        'post_routes' => array_filter($routes, function($route) {
            return in_array('POST', $route['methods']);
        })
    ], 200, [], JSON_PRETTY_PRINT);
});
    Route::get('/words/create', [WordController::class, 'create'])->name('words.create');
    Route::post('/words', [WordController::class, 'store'])->name('words.store');
    Route::get('/words/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::put('/words/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/words/{word}', [WordController::class, 'destroy'])->name('words.destroy');

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
});

Route::middleware('auth')->group(function () {
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/question', [QuizController::class, 'question'])->name('quiz.question');
    Route::post('/quiz/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::get('/quiz/results', [QuizController::class, 'complete'])->name('quiz.results');
    Route::get('/quiz/leaderboard', [QuizController::class, 'leaderboard'])->name('quiz.leaderboard');
    Route::post('/quiz/abort', [QuizController::class, 'abort'])->name('quiz.abort');
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
        $debug['latest_migration'] = DB::table('migrations')->latest()->first();
        
        $debug['app_env'] = config('app.env');
        $debug['app_debug'] = config('app.debug');
        
    } catch (\Exception $e) {
        $debug['error'] = $e->getMessage();
        $debug['database_connected'] = false;
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});






// Add this route to your web.php temporarily for debugging
Route::middleware('auth')->post('/debug-quiz-start', function (Request $request) {
    try {
        // Log everything about the request
        \Log::info('Debug Quiz Start - Request received', [
            'method' => $request->method(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'all_input' => $request->all(),
            'headers' => $request->headers->all(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);
        
        // Validate the same way as original
        $request->validate([
            'word_count' => 'required|integer|min:5|max:100'
        ]);

        $wordCount = $request->word_count;
        $totalAvailable = \App\Models\Word::count();

        if ($wordCount > $totalAvailable) {
            \Log::warning('Debug Quiz Start - Not enough words', [
                'requested' => $wordCount,
                'available' => $totalAvailable
            ]);
            
            return redirect()->route('quiz.index')
                ->with('error', "Only {$totalAvailable} words are available in the database.");
        }

        // Get random words for the quiz
        $words = \App\Models\Word::inRandomOrder()
            ->limit($wordCount)
            ->get(['id', 'greek_word', 'georgian_translation'])
            ->toArray();

        \Log::info('Debug Quiz Start - Words retrieved', [
            'word_count' => count($words),
            'first_word' => $words[0] ?? null
        ]);

        // Store quiz data in session
        $quizData = [
            'words' => $words,
            'current_question' => 0,
            'score' => 0,
            'total_questions' => $wordCount,
            'user_answers' => [],
            'start_time' => now()
        ];
        
        Session::put('quiz_data', $quizData);
        
        \Log::info('Debug Quiz Start - Session data stored', [
            'session_has_data' => Session::has('quiz_data'),
            'quiz_data_count' => count(Session::get('quiz_data', []))
        ]);

        \Log::info('Debug Quiz Start - Redirecting to question');
        
        return redirect()->route('quiz.question');
        
    } catch (\Throwable $e) {
        \Log::error('Debug Quiz Start - Exception occurred', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Exception in debug quiz start',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Also add a simple form to test this debug route
Route::middleware('auth')->get('/debug-quiz-form', function() {
    return '<html><body>
        <h1>Debug Quiz Form</h1>
        <form action="/debug-quiz-start" method="POST">
            ' . csrf_field() . '
            <label>Word Count: 
                <select name="word_count">
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                </select>
            </label>
            <button type="submit">Start Debug Quiz</button>
        </form>
        
        <hr>
        <h2>Original Form Test</h2>
        <form action="' . route('quiz.start') . '" method="POST">
            ' . csrf_field() . '
            <label>Word Count: 
                <select name="word_count">
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                </select>
            </label>
            <button type="submit">Start Original Quiz</button>
        </form>
        
        <script>
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    console.log("Submitting form to:", this.action);
                    console.log("Method:", this.method);
                    console.log("Data:", new FormData(this));
                });
            });
        </script>
    </body></html>';
});