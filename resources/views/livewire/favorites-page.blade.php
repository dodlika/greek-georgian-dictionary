<div class="container py-5">
    <div class="mx-auto" style="max-width: 768px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold text-primary">My Favorite Words</h1>
            <a href="{{ route('words.index') }}" class="btn btn-primary">
                Browse Words
            </a>
             <a href="{{ route('favorites.flashcards') }}" class="btn btn-outline-primary">
            Take Quiz
        </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($favoriteWords->count() > 0)
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" fill="currentColor" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill"/>
                </svg>
                <div>
                    You have {{ $favoriteWords->total() }} favorite {{ Str::plural('word', $favoriteWords->total()) }}
                </div>
            </div>

            <div class="row g-4 mb-4">
                @foreach($favoriteWords as $word)
                    <div class="col-12">
                        <div class="card shadow-sm border-0 hover-shadow">
                            <div class="card-body d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h3 class="card-title text-primary fw-bold mb-2">{{ $word->greek_word }}</h3>

                                    <p class="card-text mb-2">
                                        <strong>Georgian:</strong> {{ $word->georgian_translation }}
                                    </p>

                                    <div class="mb-3 d-flex flex-wrap gap-3 text-muted small">
                                        <div>
                                            <strong>Type:</strong>
                                            <span class="badge bg-secondary">{{ $word->word_type }}</span>
                                        </div>

                                        @if($word->pronunciation)
                                            <div>
                                                <strong>Pronunciation:</strong>
                                                <em>{{ $word->pronunciation }}</em>
                                            </div>
                                        @endif
                                    </div>

                                    @if($word->example_sentence)
                                        <p class="card-text text-muted mb-2">
                                            <strong>Example:</strong>
                                            <em>{{ $word->example_sentence }}</em>
                                        </p>
                                    @endif

                                    @if($word->notes)
                                        <p class="card-text text-muted">
                                            <strong>Notes:</strong> {{ $word->notes }}
                                        </p>
                                    @endif
                                </div>

                                <button 
                                    wire:click="removeFromFavorites({{ $word->id }})"
                                    wire:confirm="Are you sure you want to remove this word from favorites?"
                                    class="btn btn-outline-danger btn-sm ms-3"
                                    title="Remove from favorites"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

<div class="d-flex justify-content-center mt-4">
       {{ $favoriteWords->links('livewire::bootstrap') }}


    </div>

        @else
            <div class="text-center py-5 bg-light rounded">
                <svg class="mb-4" width="64" height="64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>

                <h2 class="h4 fw-semibold mb-3 text-secondary">No favorite words yet</h2>
                <p class="text-muted mb-4">Start building your vocabulary by adding words to your favorites!</p>

                <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                    <a href="{{ route('words.index') }}" class="btn btn-primary px-4">
                        Browse All Words
                    </a>

                    @if(auth()->user()->can_manage_words)
                        <a href="{{ route('words.create') }}" class="btn btn-success px-4">
                            Add New Word
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</div>

