<div class="container mx-auto p-4" style="max-width: 500px; text-align: center;">

    @if(count($words) === 0)
        <p class="text-secondary">You have no favorite words to display flashcards.</p>
    @else
        <a href="{{ route('words.index') }}" class="btn btn-primary px-4 py-2 my-5">Browse words</a>

        <div class="bg-white rounded shadow p-4 mb-3" style="min-height: 200px; cursor: pointer; user-select: none;"
             wire:click="flipCard"
        >
            @if (!$showBack)
                <div class="display-4 fw-bold mb-4">
                    {{ $words[$currentIndex]['greek_word'] }}
                </div>
                <div class="text-muted">Tap card to flip</div>
            @else
                <div class="h4 fw-semibold mb-2">
                    {{ $words[$currentIndex]['georgian_translation'] }}
                </div>
                <div class="small text-muted mb-1">Type: {{ $words[$currentIndex]['word_type'] }}</div>
                @if(!empty($words[$currentIndex]['example_sentence']))
                    <div class="fst-italic text-secondary mb-1">"{{ $words[$currentIndex]['example_sentence'] }}"</div>
                @endif
            @endif
        </div>

        <div class="d-flex justify-content-between">
            <button wire:click="prevCard" class="btn btn-secondary px-4 py-2">
                Previous
            </button>
            <button wire:click="nextCard" class="btn btn-primary px-4 py-2">
                Next
            </button>
        </div>
    @endif

</div>
