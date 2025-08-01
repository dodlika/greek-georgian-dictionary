<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateWordRequest;
use App\Models\Word;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordController extends Controller
{

    public function checkDuplicate(Request $request)
    {
        $greekWord = $request->input('greek_word');

        if (!$greekWord) {
            return response()->json(['exists' => false]);
        }

        $exists = Word::where('greek_word', $greekWord)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function autocomplete(Request $request)
    {
        $search = $request->input('query');

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        // Basic autocomplete + fuzzy matching
        $words = Word::where('greek_word', 'LIKE', "{$search}%")
            ->orWhere('greek_word', 'LIKE', "%{$search}%")
            ->orderBy('greek_word')
            ->limit(10)
            ->pluck('greek_word');

        return response()->json($words);
    }

    public function index(Request $request)
    {
        // Greek uppercase letters
        $greekAlphabetUpper = ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ',
                              'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω'];

        // Greek lowercase letters (corresponding to uppercase ones)
        $greekAlphabetLower = ['α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ',
                              'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω'];

        // Georgian alphabet (add as needed)
        $georgianAlphabet = ['ა', 'ბ', 'გ', 'დ', 'ე', 'ვ', 'ზ', 'თ', 'ი', 'კ', 'ლ', 'მ',
                             'ნ', 'ო', 'პ', 'ჟ', 'რ', 'ს', 'ტ', 'უ', 'ფ', 'ქ', 'ღ', 'ყ',
                             'შ', 'ჩ', 'ც', 'ძ', 'წ', 'ჭ', 'ხ', 'ჯ', 'ჰ'];

        // Get distinct word types from DB for filter dropdown
        $types = Word::select('word_type')->distinct()->orderBy('word_type')->pluck('word_type')->toArray();

        $query = Word::select('words.*')
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MIN(id)')
                    ->from('words')
                    ->groupBy('greek_word');
            });

        // Remove the verb exclusion since we want to show all words now
        // ->where('word_type', '!=', 'verb');  // Exclude verbs here

        // Search by Georgian text (assuming you have a searchGeorgian scope)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->searchGeorgian($search);
        }

        // Filter by starts_with - Greek letter case insensitive
        if ($request->filled('starts_with')) {
            $startsWith = $request->get('starts_with');

            $query->where(function ($q) use ($startsWith) {
                $upper = mb_strtoupper($startsWith);
                $lower = mb_strtolower($startsWith);

                $q->where('greek_word', 'LIKE', $upper . '%')
                  ->orWhere('greek_word', 'LIKE', $lower . '%');
            });
        }

        // Filter by word_type if specified
        if ($request->filled('type')) {
            $query->where('word_type', $request->get('type'));
        }

        $query->orderBy('greek_word', 'asc');

        $words = $query->paginate(20);

   $words->getCollection()->transform(function ($word) {
    if ($word->word_type === 'verb') {
        // Only decode if it's a string
        foreach (['present_tense', 'past_tense', 'future_tense'] as $tense) {
            if (is_string($word->$tense)) {
                $word->$tense = json_decode($word->$tense, true);
            }
        }
    }
    return $word;
});


        return view('words.index', compact(
            'words',
            'greekAlphabetUpper',
            'greekAlphabetLower',
            'georgianAlphabet',
            'types'
        ));
    }

    public function create()
    {
        // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
        if (!Auth::check() || !Auth::user()->can_manage_words) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to create words.');
        }

        // $types = Word::select('word_type')->distinct()->orderBy('word_type')->pluck('word_type')->toArray();
        $types = cache()->remember('word_types', 3600, function () {
            return Word::select('word_type')->distinct()->orderBy('word_type')->pluck('word_type')->toArray();
    });

        return view('words.create', compact('types'));
    }

    public function store(StoreUpdateWordRequest $request)
{
    // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
    if (!Auth::check() || !Auth::user()->can_manage_words) {
        return redirect()->route('words.index')
            ->with('error', 'You do not have permission to create words.');
    }

    $exists = Word::where('greek_word', $request->greek_word)->exists();
    if ($exists) {
        return redirect()->back()->withInput()->with('error', 'This Greek word already exists.');
    }

    $data = $request->all();

    // If it's a verb, encode the tense arrays as JSON
    if ($request->word_type === 'verb') {
        if ($request->has('present_tense')) {
            $data['present_tense'] = json_encode($request->present_tense);
        }
        if ($request->has('past_tense')) {
            $data['past_tense'] = json_encode($request->past_tense);
        }
        if ($request->has('future_tense')) {
            $data['future_tense'] = json_encode($request->future_tense);
        }
        
        // Clear the simple tense fields for verbs
        $data['greek_present'] = null;
        $data['greek_past'] = null;
        $data['greek_future'] = null;
    } else {
        // For non-verbs, clear the complex tense fields
        $data['present_tense'] = null;
        $data['past_tense'] = null;
        $data['future_tense'] = null;
        $data['english_translation'] = null;
    }

    Word::create($data);

    return redirect()->route('words.index')
        ->with('success', 'Word added successfully!');
}


    public function edit(Word $word)
    {
        // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
        if (!Auth::check() || !Auth::user()->can_manage_words) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to edit words.');
        }

        // Pass word and types for select dropdown
        $types = Word::select('word_type')->distinct()->orderBy('word_type')->pluck('word_type')->toArray();
        return view('words.edit', compact('word', 'types'));
    }

    public function update(StoreUpdateWordRequest $request, Word $word)
{
    if (!Auth::check() || !Auth::user()->can_manage_words) {
        return redirect()->route('words.index')
            ->with('error', 'You do not have permission to update words.');
    }

    // Save original values before update
    $originalWord = $word->getOriginal();

    $data = $request->all();

    // If it's a verb, encode the tense arrays as JSON
    if ($request->word_type === 'verb') {
        if ($request->has('present_tense')) {
            $data['present_tense'] = json_encode($request->present_tense);
        }
        if ($request->has('past_tense')) {
            $data['past_tense'] = json_encode($request->past_tense);
        }
        if ($request->has('future_tense')) {
            $data['future_tense'] = json_encode($request->future_tense);
        }
        
        // Clear the simple tense fields for verbs
        $data['greek_present'] = null;
        $data['greek_past'] = null;
        $data['greek_future'] = null;
    } else {
        // For non-verbs, clear the complex tense fields
        $data['present_tense'] = null;
        $data['past_tense'] = null;
        $data['future_tense'] = null;
        $data['english_translation'] = null;
    }

    // Update the word with new data
    $word->update($data);

    // Check if important fields changed
    $changed = false;
    $fieldsToCheck = ['greek_word', 'georgian_translation', 'word_type'];
    foreach ($fieldsToCheck as $field) {
        if ($originalWord[$field] != $word->$field) {
            $changed = true;
            break;
        }
    }

    if ($changed) {
        // Notify all users who have favorited this word, regardless of can_manage_words
        $favoritingUsers = $word->favoritedByUsers; // we'll add this relation below

        foreach ($favoritingUsers as $user) {
            $user->notify(new \App\Notifications\FavoriteWordChanged($word));
        }
    }

    return redirect()->route('words.index')->with('success', 'Word updated successfully!');
}

    public function destroy(Word $word)
    {
        // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
        if (!Auth::check() || !Auth::user()->can_manage_words) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to delete words.');
        }

        $word->delete();

        return redirect()->route('words.index')->with('success', 'Word deleted successfully!');
    }
}