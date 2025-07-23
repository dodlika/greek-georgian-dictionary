<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Word;

class WordSearch extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        $this->results = [];
        
        if (strlen($this->search) >= 2) {
            $this->results = Word::where('greek_word', 'like', '%' . $this->search . '%')
                ->orWhere('georgian_translation', 'like', '%' . $this->search . '%')
                ->orWhere('greek_present', 'like', '%' . $this->search . '%')
                ->orWhere('greek_past', 'like', '%' . $this->search . '%')
                ->orWhere('greek_future', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        }
    }

    public function highlightText($text, $search)
    {
        if (empty($search) || empty($text)) {
            return $text;
        }

        // Case insensitive highlighting
        $highlighted = preg_replace('/(' . preg_quote($search, '/') . ')/iu', '<mark class="search-highlight">$1</mark>', $text);
        
        return $highlighted;
    }

    public function render()
    {
        return view('livewire.word-search');
    }
}