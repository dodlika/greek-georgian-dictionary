<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $types = Word::select('word_type')->distinct()->orderBy('word_type')->pluck('word_type')->toArray();
        return view('words.create', compact('types'));
    }

    public function store(Request $request)
    {
        // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
        if (!Auth::check() || !Auth::user()->can_manage_words) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to create words.');
        }

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

    public function update(Request $request, Word $word)
    {
        // Check if user is authenticated and can manage words - DIRECT PROPERTY ACCESS
        if (!Auth::check() || !Auth::user()->can_manage_words) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to update words.');
        }

        $request->validate([
            'greek_word' => 'required|string|max:255',
            'greek_present' => 'nullable|string|max:255',
            'greek_past' => 'nullable|string|max:255',
            'greek_future' => 'nullable|string|max:255',
            'georgian_translation' => 'required|string|max:255',
            'word_type' => 'required|string|max:255',
        ]);

        $word->update($request->all());

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