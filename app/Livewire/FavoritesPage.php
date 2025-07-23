<?php  

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class FavoritesPage extends Component
{
    use WithPagination;

      public function removeFromFavorites($wordId)
    {
        $user = Auth::user();
        if ($user) {
            $user->favoriteWords()->detach($wordId);
            session()->flash('success', 'Word removed from favorites.');
            // Optionally refresh favoriteWords or emit events here
        }
    }
public function render()
{
    $user = Auth::user();

    $favoriteWords = $user->favoriteWords()->orderBy('greek_word')->paginate(20);
   return view('livewire.favorites-page', [
            'favoriteWords' => $favoriteWords
        ])->extends('layouts.app')->section('content');
}


}
