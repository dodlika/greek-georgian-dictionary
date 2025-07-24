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

        // Search by Georgian text (assuming you have a searchGeorgian scope)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->searchGeorgian($search);
        }

        // Filter by starts_with - Greek letter case insensitive
        if ($request->filled('starts_with')) {
            $startsWith = $request->get('starts_with');

            // Try both uppercase and lowercase variants for filtering
            $query->where(function ($q) use ($startsWith, $greekAlphabetUpper, $greekAlphabetLower) {
                $upper = mb_strtoupper($startsWith);
                $lower = mb_strtolower($startsWith);

                // Also safeguard if user enters something outside Greek alphabets
                if (in_array($upper, $greekAlphabetUpper) || in_array($lower, $greekAlphabetLower)) {
                    $q->where('greek_word', 'LIKE', $upper . '%')
                      ->orWhere('greek_word', 'LIKE', $lower . '%');
                } else {
                    // fallback if letter not Greek, just use raw input as uppercase and lowercase
                    $q->where('greek_word', 'LIKE', $upper . '%')
                      ->orWhere('greek_word', 'LIKE', $lower . '%');
                }
            });
        }

        // Filter by word_type
        if ($request->filled('type')) {
            $query->where('word_type', $request->get('type'));
        }

        $query->orderBy('greek_word', 'asc');

        $words = $query->paginate(20);
        
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

        Word::create($request->all());

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

    // Check what changed
    $originalWord = $word->getOriginal(); // old attributes before update
    $newData = $request->only(['greek_word', 'georgian_translation', 'word_type']); // fields to check

    $word->update($request->all());

    // Determine if something important changed
    $changed = false;
    foreach ($newData as $field => $newValue) {
        if ($originalWord[$field] != $newValue) {
            $changed = true;
            break;
        }
    }

    if ($changed) {
        // Notify user (assuming current user is owner of this favorite word)
        $user = Auth::user();
        $user->notify(new \App\Notifications\FavoriteWordChanged($word));
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