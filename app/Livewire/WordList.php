<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class WordList extends Component
{
    use WithPagination;

 public int|null $expandedWordId = null;


    public $search = '';
    public $type = '';
    public $starts_with = '';

    // Remove 'page' from queryString - WithPagination handles it automatically
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'starts_with' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedStartsWith()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearType()
    {
        $this->type = '';
        $this->resetPage();
    }

    public function clearStartsWith()
    {
        $this->starts_with = '';
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->starts_with = '';
        $this->resetPage();
    }

    public function highlightText($text, $search)
    {
        if (empty($search) || empty($text)) {
            return $text;
        }

        // Escape special regex characters in search term
        $escapedSearch = preg_quote($search, '/');
        
        // Case insensitive highlighting with word boundaries for better matching
        $highlighted = preg_replace('/(' . $escapedSearch . ')/iu', '<mark class="search-highlight">$1</mark>', $text);
        
        return $highlighted;
    }


public function toggleFavorite($wordId)
{
    Log::info('Toggling favorite for word ID: ' . $wordId);

    $user = auth()->user();
    if (!$user) {
        Log::error('No authenticated user');
        return;
    }

    if ($user->favoriteWords()->where('word_id', $wordId)->exists()) {
        Log::info('Detaching favorite');
        $user->favoriteWords()->detach($wordId);
    } else {
        Log::info('Attaching favorite');
        $user->favoriteWords()->attach($wordId);
    }
}

    public function toggleExpand($wordId)
    {
        $this->expandedWordId = $this->expandedWordId === $wordId ? null : $wordId;
    }

public function isFavorited($wordId)
{
    if (!Auth::check()) {
        return false;
    }
    return Auth::user()->favoriteWords()->where('word_id', $wordId)->exists();
}


    public function render()
    {
        // Base query - get unique words by greek_word
        $query = Word::select('words.*')
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MIN(id)')
                    ->from('words')
                    ->groupBy('greek_word');
            });

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = trim($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('greek_word', 'like', '%' . $searchTerm . '%')
                  ->orWhere('georgian_translation', 'like', '%' . $searchTerm . '%')
                  ->orWhere('greek_present', 'like', '%' . $searchTerm . '%')
                  ->orWhere('greek_past', 'like', '%' . $searchTerm . '%')
                  ->orWhere('greek_future', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply type filter
        if (!empty($this->type)) {
            $query->where('word_type', $this->type);
        }

        // Apply starts_with filter
        if (!empty($this->starts_with)) {
            $starts = $this->starts_with;
            $query->where(function($q) use ($starts) {
                $upper = mb_strtoupper($starts);
                $lower = mb_strtolower($starts);
                $q->where('greek_word', 'like', $upper . '%')
                  ->orWhere('greek_word', 'like', $lower . '%');
            });
        }

        // Get paginated results
        $words = $query->orderBy('greek_word', 'asc')->paginate(20);

        // Get distinct word types for filter dropdown
        $types = Word::select('word_type')
            ->distinct()
            ->whereNotNull('word_type')
            ->where('word_type', '!=', '')
            ->orderBy('word_type')
            ->pluck('word_type');

        // Greek alphabet for letter filters
        $greekAlphabetUpper = [
            'Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ',
            'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω'
        ];

        return view('livewire.word-list', compact('words', 'types', 'greekAlphabetUpper'));
    }
}