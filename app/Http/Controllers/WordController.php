<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
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

    $query = Word::query();

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

    $words = $query->paginate(20);
$query->orderBy('greek_word', 'asc');
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
        return view('words.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'greek_word' => 'required|string|max:255',
            'greek_present' => 'nullable|string|max:255',
            'greek_past' => 'nullable|string|max:255',
            'greek_future' => 'nullable|string|max:255',
            'georgian_translation' => 'required|string|max:255',
            'word_type' => 'required|string|max:255',
        ]);

        Word::create($request->all());

        return redirect()->route('words.index')
            ->with('success', 'Word added successfully!');
    }
}