<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index(Request $request)
{
    $query = Word::query();

    // Apply search filter
    if ($request->filled('search')) {
        $query->searchGeorgian($request->get('search'));
    }

    // Apply "starts with" Greek letter filter
    if ($request->filled('starts_with')) {
        $query->where('greek_word', 'LIKE', $request->get('starts_with') . '%');
    }

    // Apply "word_type" filter
    if ($request->filled('word_type')) {
        $query->where('word_type', $request->get('word_type'));
    }

    $words = $query->paginate(20);

    // Define Greek alphabet for filtering UI
    $greekLetters = ['Α','Β','Γ','Δ','Ε','Ζ','Η','Θ','Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω'];

    // Optional: If you want to include Georgian letters too
    $georgianLetters = ['ა','ბ','გ','დ','ე','ვ','ზ','თ','ი','კ','ლ','მ','ნ','ო','პ','ჟ','რ','ს','ტ','უ','ფ','ქ','ღ','ყ','შ','ჩ','ც','ძ','წ','ჭ','ხ','ჯ','ჰ'];

    return view('words.index', compact('words', 'greekLetters', 'georgianLetters'));
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