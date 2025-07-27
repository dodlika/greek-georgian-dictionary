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
        
        // Count available verbs for verb tense quiz
        $totalVerbs = Word::where('word_type', 'verb')
            ->whereNotNull('present_tense')
            ->whereNotNull('past_tense')
            ->whereNotNull('future_tense')
            ->count();
        
        return view('quiz.index', compact('user', 'totalWords', 'totalVerbs'));
    }

    public function wordCount(Request $request)
    {
        $addedAfter = $request->query('added_after');
        $quizType = $request->query('quiz_type', 'vocabulary');

        if ($quizType === 'verb_tense') {
            $query = Word::where('word_type', 'verb')
                ->whereNotNull('present_tense')
                ->whereNotNull('past_tense')
                ->whereNotNull('future_tense');

            if ($addedAfter) {
                $query->whereDate('created_at', '>=', $addedAfter);
            }

            return response()->json(['word_count' => $query->count()]);
        }

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
        $quizDirection = $request->input('quiz_direction', 'greek_to_georgian');
        $quizType = $request->input('quiz_type', 'vocabulary');
        $verbTenseType = $request->input('verb_tense_type', 'mixed');
        $verbPersonType = $request->input('verb_person_type', 'mixed');

        if ($quizType === 'verb_tense') {
            $request->validate([
                'verb_tense_type' => 'required|in:past,future,mixed'
            ]);

            $query = Word::where('word_type', 'verb')
                ->whereNotNull('present_tense')
                ->whereNotNull('past_tense')
                ->whereNotNull('future_tense');

            if ($addedAfter) {
                $query->whereDate('created_at', '>=', $addedAfter);
                $words = $query->inRandomOrder()->get([
                    'id', 'greek_word', 'georgian_translation', 'english_translation', 
                    'present_tense', 'past_tense', 'future_tense'
                ]);

                if ($words->count() < 5 && !$forceStart) {
                    return redirect()->route('quiz.index')
                        ->with('error', "Only {$words->count()} verb(s) available after the selected date.");
                }

                $wordCount = $words->count();
            } else {
                $request->validate([
                    'word_count' => 'required|integer|min:5|max:100'
                ]);

                $wordCount = $request->input('word_count');
                $words = $query->inRandomOrder()->limit($wordCount)->get([
                    'id', 'greek_word', 'georgian_translation', 'english_translation',
                    'present_tense', 'past_tense', 'future_tense'
                ]);
            }

            Session::put('quiz_data', [
                'words' => $words->toArray(),
                'current_question' => 0,
                'score' => 0,
                'total_questions' => $wordCount,
                'user_answers' => [],
                'start_time' => now(),
                'quiz_type' => 'verb_tense',
                'verb_tense_type' => $verbTenseType
            ]);
        } else {
            // Original vocabulary quiz logic
            $request->validate([
                'quiz_direction' => 'required|in:greek_to_georgian,georgian_to_greek'
            ]);

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
                'start_time' => now(),
                'quiz_type' => 'vocabulary',
                'quiz_direction' => $quizDirection
            ]);
        }

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

        // For verb tense quiz, determine which tense to test
        if (($quizData['quiz_type'] ?? 'vocabulary') === 'verb_tense') {
            $verbTenseType = $quizData['verb_tense_type'] ?? 'mixed';
            
            // Determine tense to test (always 1st person singular)
            if ($verbTenseType === 'mixed') {
                $tenseOptions = ['past', 'future'];
                $tenseToTest = $tenseOptions[array_rand($tenseOptions)];
            } else {
                $tenseToTest = $verbTenseType;
            }
            
            // Always use 1st person singular
            $personToTest = '1st_singular';
            
            // Store what we're testing for this question
            $quizData['current_tense'] = $tenseToTest;
            $quizData['current_person'] = $personToTest;
            Session::put('quiz_data', $quizData);
        }

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
        $quizType = $quizData['quiz_type'] ?? 'vocabulary';
        
        $isCorrect = false;

        if ($quizType === 'verb_tense') {
            // Handle verb tense quiz
            $tenseToTest = $quizData['current_tense'];
            $personToTest = $quizData['current_person'];
            
           $tenseField = $currentWord[$tenseToTest . '_tense'];
$tenseData = is_string($tenseField) ? json_decode($tenseField, true) : $tenseField;

            $correctAnswerData = $tenseData[$personToTest];
            $correctGreekForm = $correctAnswerData['greek'];
            
            // Check if user answer matches the correct Greek form
            if (strtolower(trim($correctGreekForm)) === $userAnswer) {
                $isCorrect = true;
            }
            
            // Store answer data
            $quizData['user_answers'][$currentQuestion] = [
                'user_answer' => $request->input('answer'),
                'correct_answer' => $correctGreekForm,
                'correct_georgian' => $correctAnswerData['georgian'],
                'correct_english' => $correctAnswerData['english'],
                'is_correct' => $isCorrect,
                'question_word' => $currentWord['greek_word'],
                'quiz_type' => 'verb_tense',
                'tense_tested' => $tenseToTest,
                'person_tested' => $personToTest,
                'georgian_translation' => $currentWord['georgian_translation'] ?? '',
          'present_form' => $currentWord['present_tense']['1st_singular']['greek']

            ];
        } else {
            // Original vocabulary quiz logic
            $quizDirection = $quizData['quiz_direction'] ?? 'greek_to_georgian';
            
            if ($quizDirection === 'greek_to_georgian') {
                $correctAnswerField = $currentWord['georgian_translation'];
                $questionField = $currentWord['greek_word'];
            } else {
                $correctAnswerField = $currentWord['greek_word'];
                $questionField = $currentWord['georgian_translation'];
            }

            // Split correct answers by comma and trim each alternative
            $correctAnswers = array_map('trim', explode(',', $correctAnswerField));
            $correctAnswers = array_map('strtolower', $correctAnswers);

            // Extract parenthetical alternatives
            $parentheticalAnswers = [];
            preg_match_all('/\(([^)]+)\)/', $correctAnswerField, $matches);
            foreach ($matches[1] as $match) {
                $parentheticalAnswers[] = trim(strtolower($match));
            }

            // Clean main answers by removing parentheses content
            $cleanedAnswers = [];
            foreach ($correctAnswers as $answer) {
                $cleaned = preg_replace('/\s*\([^)]*\)/', '', $answer);
                $cleanedAnswers[] = trim($cleaned);
            }

            // Combine all possible correct answers
            $allCorrectAnswers = array_merge($cleanedAnswers, $parentheticalAnswers);
            $allCorrectAnswers = array_filter($allCorrectAnswers);

            foreach ($allCorrectAnswers as $correctAnswer) {
                if ($userAnswer === $correctAnswer) {
                    $isCorrect = true;
                    break;
                }
            }

            $quizData['user_answers'][$currentQuestion] = [
                'user_answer' => $request->input('answer'),
                'correct_answer' => $correctAnswerField,
                'is_correct' => $isCorrect,
                'question_word' => $questionField,
                'quiz_direction' => $quizDirection,
                'quiz_type' => 'vocabulary'
            ];
        }

        // Update score
        if ($isCorrect) {
            $quizData['score']++;
        }

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
            'answers' => $quizData['user_answers'],
            'quiz_type' => $quizData['quiz_type'] ?? 'vocabulary'
        ];
        
        $incorrectWords = collect($quizData['user_answers'])
            ->filter(fn ($answer) => !$answer['is_correct'])
            ->pluck('question_word')
            ->values()
            ->all();

        Session::put('quiz_incorrect_words', $incorrectWords);

        // Clear quiz data from session
        Session::forget('quiz_data');

        return view('quiz.results', compact('results', 'user'));
    }

    public function saveIncorrectToFavorites()
    {
        $user = Auth::user();
        $incorrectWords = Session::get('quiz_incorrect_words', []);

        if (empty($incorrectWords)) {
            return redirect()->route('quiz.index')->with('error', 'No incorrect words to save.');
        }

        // Find words by either Greek or Georgian text since quiz can be bidirectional
        $words = Word::where(function($query) use ($incorrectWords) {
            $query->whereIn('greek_word', $incorrectWords)
                  ->orWhereIn('georgian_translation', $incorrectWords);
        })->get();

        foreach ($words as $word) {
            // Attach if not already favorited
            if (!$user->favoriteWords->contains($word->id)) {
                $user->favoriteWords()->attach($word->id);
            }
        }

        // Clear session after saving
        Session::forget('quiz_incorrect_words');

        return redirect()->route('words.index')->with('success', 'Incorrect words saved to your favorites!');
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