<div class="word-list-component">
    <!-- Search and Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Search Input -->
            <div class="row mb-3">
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

            <!-- Greek Alphabet Filter -->
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

            <!-- Active Filters Display -->
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

    <!-- Results Count -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">
                Words 
                @if($search || $type || $starts_with)
                    <small class="text-muted">({{ $words->total() }} results found)</small>
                @else
                    <small class="text-muted">({{ $words->total() }} total)</small>
                @endif
            </h5>
        </div>
        
        @if(auth()->check() && auth()->user()->can_manage_words)
            <a href="{{ route('words.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Word
            </a>
        @endif
    </div>

    <!-- Results Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Greek Word</th>
                    <th>Present</th>
                    <th>Past</th>
                    <th>Future</th>
                    <th>Georgian Translation</th>
                    <th>Type</th>
                    @if(auth()->check() && auth()->user()->can_manage_words)
                        <th width="150">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($words as $word)
                    <tr>
                        <td class="greek-text fw-bold">
                            {!! $this->highlightText($word->greek_word, $search) !!}
                        </td>
                        <td class="greek-text">
                            {!! $word->greek_present ? $this->highlightText($word->greek_present, $search) : '-' !!}
                        </td>
                        <td class="greek-text">
                            {!! $word->greek_past ? $this->highlightText($word->greek_past, $search) : '-' !!}
                        </td>
                        <td class="greek-text">
                            {!! $word->greek_future ? $this->highlightText($word->greek_future, $search) : '-' !!}
                        </td>
                        <td class="georgian-text fw-bold">
                            {!! $this->highlightText($word->georgian_translation, $search) !!}
                        </td>
            
                        <td class="voice-wrap">
                            @if($word->word_type)
                                <span class="badge bg-info">{{ $word->word_type }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                              <button class="btn btn-sm btn-outline-primary" onclick="speak('{{ $word->greek_word }}')">
                🔊
            </button>
                        </td>
                        @if(auth()->check() && auth()->user()->can_manage_words)
                            <td>
                                <div class="btn-group d-flex gap-3" role="group">
                                    <a href="{{ route('words.edit', $word) }}" class="btn btn-sm btn-warning" title="Edit word">
                                        კორექტირება
                                    </a>
                                    <form action="{{ route('words.destroy', $word) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this word?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Delete word">
                                            წაშლა
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->check() && auth()->user()->can_manage_words ? 7 : 6 }}" class="text-center py-4">
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

    <!-- Loading indicator -->
    <div wire:loading class="text-center py-3">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="ms-2 text-muted">Searching...</span>
    </div>

    <style>
        .voice-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

    .greek-alphabet-filter {
        max-height: 120px;
        overflow-y: auto;
    }

    .active-filters {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .search-highlight {
            background-color: #ffc107;
            color: #000;
        }
    }

    @media (max-width: 768px) {
        .greek-alphabet-filter {
            max-height: 80px;
        }
        
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }
        
        .search-highlight {
            padding: 1px 2px;
            font-size: 0.95em;
        }
    }
    </style>

  

</div>