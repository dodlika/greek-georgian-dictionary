<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FavoriteFlashcards extends Component
{
    public $words = [];
    public $currentIndex = 0;
    public $showBack = false;

    public function mount()
    {
        $user = Auth::user();
        $this->words = $user->favoriteWords()->orderBy('greek_word')->get()->toArray();
    }

    public function flipCard()
    {
        $this->showBack = !$this->showBack;
    }

    public function nextCard()
    {
        $this->showBack = false;
        if ($this->currentIndex < count($this->words) - 1) {
            $this->currentIndex++;
        } else {
            $this->currentIndex = 0; // loop back to start
        }
    }

    public function prevCard()
    {
        $this->showBack = false;
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        } else {
            $this->currentIndex = count($this->words) - 1; // loop to end
        }
    }

    public function render()
    {
        return view('livewire.favorite-flashcards')->extends('layouts.app')->section('content');;
    }
}
