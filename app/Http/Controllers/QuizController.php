<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller; 

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $totalWords = Word::count();
        
        return view('quiz.index', compact('user', 'totalWords'));
    }

    public function wordCount(Request $request)
{
    $addedAfter = $request->query('added_after');

    if (!$addedAfter) {
        return response()->json(['word_count' => Word::count()]);
    }

    $count = Word::whereDate('created_at', '>=', $addedAfter)->count();
    return response()->json(['word_count' => $count]);
}


    public function start(Request $request)
{
    $addedAfter = $request->input('added_after');
    $forceStart = $request->boolean('force_start');

    if ($addedAfter) {
        $words = Word::whereDate('created_at', '>=', $addedAfter)->inRandomOrder()->get([
            'id', 'greek_word', 'georgian_translation'
        ]);

        if ($words->count() < 5 && !$forceStart) {
            return redirect()->route('quiz.index')
                ->with('error', "Only {$words->count()} word(s) available after the selected date.");
        }

        $wordCount = $words->count();
    } else {
        $request->validate([
            'word_count' => 'required|integer|min:5|max:100'
        ]);

        $wordCount = $request->input('word_count');
        $words = Word::inRandomOrder()->limit($wordCount)->get([
            'id', 'greek_word', 'georgian_translation'
        ]);
    }

    Session::put('quiz_data', [
        'words' => $words->toArray(),
        'current_question' => 0,
        'score' => 0,
        'total_questions' => $wordCount,
        'user_answers' => [],
        'start_time' => now()
    ]);

    return redirect()->route('quiz.question');
}




    public function question()
    {
        $quizData = Session::get('quiz_data');

        

        if (!$quizData) {
            return redirect()->route('quiz.index')
                ->with('error', 'No active quiz found. Please start a new quiz.');
        }

        $currentQuestion = $quizData['current_question'];
        
        // Check if quiz is completed
        if ($currentQuestion >= $quizData['total_questions']) {
            return $this->complete();
        }

        $currentWord = $quizData['words'][$currentQuestion];
        $progress = (($currentQuestion + 1) / $quizData['total_questions']) * 100;

        return view('quiz.question', compact('currentWord', 'quizData', 'progress'));
    }

    public function answer(Request $request)
    {
        $request->validate([
            'answer' => 'required|string|max:500'
        ]);

        $quizData = Session::get('quiz_data');
        
        if (!$quizData) {
            return redirect()->route('quiz.index')
                ->with('error', 'No active quiz found.');
        }

        $currentQuestion = $quizData['current_question'];
        $currentWord = $quizData['words'][$currentQuestion];
        $userAnswer = trim(strtolower($request->input('answer')));
        $correctAnswer = trim(strtolower($currentWord['georgian_translation']));

        // Simple scoring - exact match or contains check
        $isCorrect = ($userAnswer === $correctAnswer) || 
                    (str_contains($correctAnswer, $userAnswer) && strlen($userAnswer) > 2) ||
                    (str_contains($userAnswer, $correctAnswer) && strlen($correctAnswer) > 2);

        if ($isCorrect) {
            $quizData['score']++;
        }

        // Store the answer
        $quizData['user_answers'][$currentQuestion] = [
            'user_answer' => $request->input('answer'),
            'correct_answer' => $currentWord['georgian_translation'],
            'is_correct' => $isCorrect,
            'greek_word' => $currentWord['greek_word']
        ];

        // Move to next question
        $quizData['current_question']++;

        Session::put('quiz_data', $quizData);

        return redirect()->route('quiz.question');
    }

    public function complete()
    {
        $quizData = Session::get('quiz_data');

        
        if (!$quizData) {
            return redirect()->route('quiz.index')
                ->with('error', 'No quiz data found.');
        }

        $user = User::find(Auth::id());
        $score = $quizData['score'];
        $total = $quizData['total_questions'];
        $percentage = round(($score / $total) * 100, 2);

        // Update user's best score if this is better
        $currentBestPercentage = $user->best_quiz_total > 0 
            ? round(($user->best_quiz_score / $user->best_quiz_total) * 100, 2) 
            : 0;

        if ($percentage > $currentBestPercentage) {
            // Use update method correctly
            $user->update([
                'best_quiz_score' => $score,
                'best_quiz_total' => $total,
                'best_quiz_date' => now(),
            ]);
        }


        // Update total quizzes taken using increment
        $user->increment('total_quizzes_taken');

        $results = [
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'is_new_best' => $percentage > $currentBestPercentage,
            'time_taken' => now()->diffInMinutes($quizData['start_time']),
            'answers' => $quizData['user_answers']
        ];

        // Clear quiz data from session
        Session::forget('quiz_data');

        return view('quiz.results', compact('results', 'user'));
    }

    public function leaderboard()
    {
        $users = User::where('best_quiz_total', '>', 0)
            ->get()
            ->map(function ($user) {
                $user->best_percentage = $user->best_quiz_total > 0
                    ? round(($user->best_quiz_score / $user->best_quiz_total) * 100, 2)
                    : 0;
                return $user;
            })

            ->sortByDesc('best_percentage')
            ->take(10);

            

        return view('quiz.leaderboard', compact('users'));
    }

    public function abort()
    {
        Session::forget('quiz_data');
        return redirect()->route('quiz.index')
            ->with('info', 'Quiz aborted successfully.');
    }
}