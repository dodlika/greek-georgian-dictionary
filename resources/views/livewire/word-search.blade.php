<div class="word-search-component">
    <div class="mb-3">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Search Greek or Georgian words..." 
            class="form-control search-input"
            autocomplete="off"
        >
    </div>

    @if(count($results) > 0)
        <div class="search-results">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $word)
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
                                <td>
                                    @if($word->word_type)
                                        <span class="badge bg-info">{{ $word->word_type }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="text-muted small mt-2">
                Found {{ count($results) }} result{{ count($results) !== 1 ? 's' : '' }}
                @if(count($results) >= 10)
                    (showing first 10)
                @endif
            </div>
        </div>
    @elseif(strlen($search) >= 2)
        <div class="alert alert-info">
            <i class="fas fa-search"></i>
            No results found for "<strong>{{ $search }}</strong>"
        </div>
    @elseif(strlen($search) > 0 && strlen($search) < 2)
        <div class="text-muted small">
            Type at least 2 characters to search...
        </div>
    @endif

    <!-- Loading indicator -->
    <div wire:loading wire:target="search" class="text-center py-3">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Searching...</span>
        </div>
        <span class="ms-2 text-muted">Searching...</span>
    </div>
</div>

<style>
.search-highlight {
    background-color: #ffeb3b;
    color: #000;
    font-weight: bold;
    padding: 1px 2px;
    border-radius: 2px;
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

.search-input {
    font-size: 1.1em;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.3s ease;
}

.search-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.search-results {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.word-search-component {
    margin-bottom: 2rem;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .search-highlight {
        background-color: #ffc107;
        color: #000;
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9em;
    }
    
    .search-input {
        font-size: 1em;
        padding: 10px 12px;
    }
}
</style>