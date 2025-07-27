<div class="word-list-component">
    <!-- Search and Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Search & Type Filters -->
            <div class="row mb-3">
                <!-- Search -->
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Words</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            class="form-control" 
                            id="search"
                            placeholder="Search Greek or Georgian words..."
                            autocomplete="off"
                        >
                        @if($search)
                            <button 
                                type="button" 
                                wire:click="clearSearch" 
                                class="btn btn-outline-secondary"
                                title="Clear search"
                            >
                                ✕
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="col-md-4">
                    <label for="type-filter" class="form-label">Word Type</label>
                    <div class="input-group">
                        <select wire:model.live="type" class="form-select" id="type-filter">
                            <option value="">All Types</option>
                            @foreach($types as $typeOption)
                                <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                            @endforeach
                        </select>
                        @if($type)
                            <button 
                                type="button" 
                                wire:click="clearType" 
                                class="btn btn-outline-secondary"
                                title="Clear type filter"
                            >
                                ✕
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Clear All Button -->
                <div class="col-md-2 d-flex align-items-end">
                    @if($search || $type || $starts_with)
                        <button 
                            type="button" 
                            wire:click="clearAllFilters" 
                            class="btn btn-warning w-100"
                        >
                            Clear All
                        </button>
                    @endif
                </div>
            </div>

            <!-- Alphabet Filter -->
            <div class="mb-3">
                <label class="form-label">Filter by First Letter</label>
                <div class="greek-alphabet-filter">
                    @foreach($greekAlphabetUpper as $letter)
                        <button
                            type="button"
                            wire:click="$set('starts_with', '{{ $letter }}')"
                            class="btn btn-sm {{ $starts_with === $letter ? 'btn-primary' : 'btn-outline-primary' }} me-1 mb-1"
                            title="Words starting with {{ $letter }}"
                        >
                            {{ $letter }}
                        </button>
                    @endforeach
                    @if($starts_with)
                        <button 
                            type="button" 
                            wire:click="clearStartsWith" 
                            class="btn btn-sm btn-danger ms-2 mb-1"
                            title="Clear letter filter"
                        >
                            Clear
                        </button>
                    @endif
                </div>
            </div>

            <!-- Active Filters Summary -->
            @if($search || $type || $starts_with)
                <div class="active-filters">
                    <small class="text-muted">Active filters:</small>
                    @if($search)
                        <span class="badge bg-info ms-1">Search: "{{ $search }}"</span>
                    @endif
                    @if($type)
                        <span class="badge bg-secondary ms-1">Type: {{ $type }}</span>
                    @endif
                    @if($starts_with)
                        <span class="badge bg-primary ms-1">Starts with: {{ $starts_with }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Results Count & Add Word Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            Words 
            <small class="text-muted">
                ({{ $words->total() }} {{ $search || $type || $starts_with ? 'results found' : 'total' }})
            </small>
        </h5>
        
        @if(auth()->check() && auth()->user()->can_manage_words)
            <a href="{{ route('words.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Word
            </a>
        @endif
    </div>

    <!-- Words Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Greek Word</th>
                    <th>Georgian Translation</th>
                    <th>Type</th>
                    <th>Actions</th>
                    @if(auth()->check() && auth()->user()->can_manage_words)
                        <th width="150">Manage</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($words as $word)
                <tr>
                    <td class="greek-text favorite-word fw-bold">
                        {!! $this->highlightText($word->greek_word, $search) !!}
                        <button wire:click.stop="toggleFavorite({{ $word->id }})" 
                                class="bg-transparent border-0 p-0 fs-5 ms-2" 
                                style="color: {{ $this->isFavorited($word->id) ? 'red' : 'gray' }};"
                                title="{{ $this->isFavorited($word->id) ? 'Unfavorite' : 'Favorite' }}">
                            {{ $this->isFavorited($word->id) ? '❤️' : '🤍' }}
                        </button>
                    </td>
                    <td class="georgian-text fw-bold">{!! $this->highlightText($word->georgian_translation, $search) !!}</td>
                    <td>
                        @if($word->word_type)
                            <span class="badge bg-info">{{ $word->word_type }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <div class="d-flex gap-2 align-items-center">
                            <!-- Speech Button -->
                            <button class="btn btn-sm btn-outline-primary" 
                                    onclick="speak('{{ $word->greek_word }}')" 
                                    title="Pronounce word">
                                🔊
                            </button>
                            
                            <!-- Expand Button for Verbs -->
                            @if($word->word_type === 'verb')
                                <button class="btn btn-sm btn-outline-info" 
                                        wire:click="toggleExpand({{ $word->id }})"
                                        title="Show conjugations">
                                    @if($expandedWordId === $word->id)
                                        📖 Hide
                                    @else
                                        📖 Show
                                    @endif
                                </button>
                            @endif
                        </div>
                    </td>
                    @if(auth()->check() && auth()->user()->can_manage_words)
                        <td>
                            <div class="btn-group d-flex gap-2" role="group">
                                <a href="{{ route('words.edit', $word) }}" class="btn btn-sm btn-warning">კორექტირება</a>
                                <form action="{{ route('words.destroy', $word) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this word?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">წაშლა</button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>

                {{-- Expandable Section for Verb Conjugations --}}
               @if($expandedWordId === $word->id && $word->word_type === 'verb')
<tr>
    <td colspan="{{ auth()->check() && auth()->user()->can_manage_words ? 5 : 4 }}" class="bg-light p-0">
        <div class="verb-conjugation-container p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <span class="greek-text fw-bold">{{ $word->greek_word }}</span> - Verb Conjugations
                </h6>
                <button class="btn btn-sm btn-outline-secondary" 
                        wire:click="toggleExpand({{ $word->id }})">
                    ✕ Close
                </button>
            </div>

            @php
                // Decode JSON strings to arrays
                $presentTense = null;
                $pastTense = null;
                $futureTense = null;
                
                if ($word->present_tense) {
                    $presentTense = is_string($word->present_tense) 
                        ? json_decode($word->present_tense, true) 
                        : $word->present_tense;
                }
                
                if ($word->past_tense) {
                    $pastTense = is_string($word->past_tense) 
                        ? json_decode($word->past_tense, true) 
                        : $word->past_tense;
                }
                
                if ($word->future_tense) {
                    $futureTense = is_string($word->future_tense) 
                        ? json_decode($word->future_tense, true) 
                        : $word->future_tense;
                }
            @endphp

            @if($presentTense || $pastTense || $futureTense)
                <div class="row">
                    {{-- Present Tense --}}
                    @if($presentTense && is_array($presentTense))
                    <div class="col-lg-4 mb-4">
                        <h6 class="text-primary border-bottom pb-2">Present Tense</h6>
                        <div class="conjugation-table">
                            @foreach($presentTense as $person => $conjugation)
                                @if(is_array($conjugation))
                                <div class="conjugation-row mb-2">
                                    <div class="person-label">
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $person)) }}:</small>
                                    </div>
                                    <div class="greek-text fw-bold">{{ $conjugation['greek'] ?? '-' }}</div>
                                    <div class="georgian-text">{{ $conjugation['georgian'] ?? '-' }}</div>
                                    @if(isset($conjugation['english']))
                                        <div class="english-text small text-muted">{{ $conjugation['english'] }}</div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Past Tense --}}
                    @if($pastTense && is_array($pastTense))
                    <div class="col-lg-4 mb-4">
                        <h6 class="text-success border-bottom pb-2">Past Tense</h6>
                        <div class="conjugation-table">
                            @foreach($pastTense as $person => $conjugation)
                                @if(is_array($conjugation))
                                <div class="conjugation-row mb-2">
                                    <div class="person-label">
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $person)) }}:</small>
                                    </div>
                                    <div class="greek-text fw-bold">{{ $conjugation['greek'] ?? '-' }}</div>
                                    <div class="georgian-text">{{ $conjugation['georgian'] ?? '-' }}</div>
                                    @if(isset($conjugation['english']))
                                        <div class="english-text small text-muted">{{ $conjugation['english'] }}</div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Future Tense --}}
                    @if($futureTense && is_array($futureTense))
                    <div class="col-lg-4 mb-4">
                        <h6 class="text-warning border-bottom pb-2">Future Tense</h6>
                        <div class="conjugation-table">
                            @foreach($futureTense as $person => $conjugation)
                                @if(is_array($conjugation))
                                <div class="conjugation-row mb-2">
                                    <div class="person-label">
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $person)) }}:</small>
                                    </div>
                                    <div class="greek-text fw-bold">{{ $conjugation['greek'] ?? '-' }}</div>
                                    <div class="georgian-text">{{ $conjugation['georgian'] ?? '-' }}</div>
                                    @if(isset($conjugation['english']))
                                        <div class="english-text small text-muted">{{ $conjugation['english'] }}</div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Additional Information --}}
                @if($word->english_translation)
                <div class="mt-3 pt-3 border-top">
                    <strong>English Translation:</strong> 
                    <span class="english-text">{{ $word->english_translation }}</span>
                </div>
                @endif
            @else
                <div class="text-muted text-center py-3">
                    <i class="fas fa-info-circle"></i>
                    No conjugation data available for this verb.
                </div>
            @endif
        </div>
    </td>
</tr>
@endif
                @empty
                <tr>
                    <td colspan="{{ auth()->check() && auth()->user()->can_manage_words ? 5 : 4 }}" class="text-center py-4">
                        @if($search || $type || $starts_with)
                            <div class="text-muted">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <p>No words found matching your search criteria.</p>
                                <button wire:click="clearAllFilters" class="btn btn-outline-primary btn-sm">
                                    Clear filters to see all words
                                </button>
                            </div>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <p>No words available.</p>
                                @if(auth()->check() && auth()->user()->can_manage_words)
                                    <a href="{{ route('words.create') }}" class="btn btn-primary btn-sm">
                                        Add the first word
                                    </a>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($words->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $words->links('livewire::bootstrap') }}
    </div>
    @endif

    <!-- Loading -->
    <div wire:loading class="text-center py-3">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="ms-2 text-muted">Searching...</span>
    </div>

    <!-- Styles -->
    <style>
        .favorite-word {
            position: relative;
        }

        .action-buttons {
            min-width: 120px;
        }

        .search-highlight {
            background-color: #ffeb3b;
            color: #000;
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 3px;
            box-decoration-break: clone;
            -webkit-box-decoration-break: clone;
        }

        .greek-text {
            font-family: 'Times New Roman', serif;
            font-size: 1.1em;
        }

        .georgian-text {
            font-family: 'BPG Nino Mtavruli', 'Sylfaen', serif;
            font-size: 1.1em;
        }

        .english-text {
            font-family: 'Arial', sans-serif;
            font-size: 0.9em;
            color: #fff;
        }

        .greek-alphabet-filter {
            max-height: 120px;
            overflow-y: auto;
        }

        .active-filters {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }

        /* Verb Conjugation Styles */
        .verb-conjugation-container {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
        }
        .verb-conjugation-container h6  {
            color: #fff;
        }
          .verb-conjugation-container h6 span  {
            color: #fff;
        }
          .verb-conjugation-container strong  {
            color: #fff;
        }
        .  .verb-conjugation-container span  {
            color: #fff !important;
        }

        .conjugation-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .conjugation-row {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #dee2e6;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }

        .conjugation-row:hover {
            border-left-color: #007bff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            background: #ffffff;
        }

        .person-label {
            font-size: 0.8em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .greek-alphabet-filter {
                max-height: 80px;
            }

            .btn-sm {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .action-buttons .d-flex {
                flex-direction: column;
                gap: 0.5rem !important;
            }

            .verb-conjugation-container .row {
                margin: 0;
            }

            .verb-conjugation-container .col-lg-4 {
                padding: 0;
                margin-bottom: 1rem;
            }

            .conjugation-row {
                padding: 6px 8px;
                margin-bottom: 0.5rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .search-highlight {
                background-color: #ffc107;
                color: #000;
            }

            .verb-conjugation-container {
                background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            }

            .conjugation-row {
                background: #f8f9fa;
                border-left-color: #dee2e6;
                color: #212529;
            }

            .conjugation-row:hover {
                border-left-color: #63b3ed;
                background: #ffffff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            }

            .conjugation-row .person-label {
                color: #6c757d;
            }
        }
    </style>

    <!-- JS for speech synthesis -->
    <script>
        function speak(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'el-GR'; // Greek language
                
                // Try to find a Greek voice
                const voices = speechSynthesis.getVoices();
                const greekVoice = voices.find(v => v.lang.startsWith('el'));
                if (greekVoice) {
                    utterance.voice = greekVoice;
                }
                
                // Set speech parameters
                utterance.rate = 0.8;
                utterance.pitch = 1;
                utterance.volume = 1;
                
                speechSynthesis.speak(utterance);
            } else {
                alert('Sorry, your browser does not support text-to-speech.');
            }
        }

        // Load voices when they become available
        if ('speechSynthesis' in window) {
            speechSynthesis.onvoiceschanged = function() {
                // Voices are now loaded
            };
        }
    </script>
</div>