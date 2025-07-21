<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index(Request $request)
{
    $query = Word::query();

    // Filter by Georgian search term
    if ($request->filled('search')) {
        $query->searchGeorgian($request->get('search'));
    }

    // Filter by word type
    if ($request->filled('word_type')) {
        $query->where('word_type', $request->get('word_type'));
    }

    // Filter by Greek first letter
    if ($request->filled('starts_with')) {
        $query->where('greek_word', 'LIKE', $request->get('starts_with') . '%');
    }

    $words = $query->paginate(20);

    return view('words.index', compact('words'));
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