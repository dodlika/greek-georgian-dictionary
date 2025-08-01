@extends('layouts.app')

@section('content')

{{-- <div class="row mb-4">
    <div class="col-md-12">
        <form method="GET" action="{{ route('words.index') }}" class="d-flex flex-wrap align-items-center gap-2">
            
            <input type="text" name="search" class="form-control me-2 georgian-text"
                    placeholder="ძიება ქართულად..." value="{{ request('search') }}" style="max-width: 250px;">
            
            <select name="type" class="form-select me-2" style="max-width: 180px;">
                <option value="">ყველა ტიპი</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="btn btn-primary me-2">ძიება</button>
            
            @if(request()->hasAny(['search', 'type', 'starts_with']))
                <a href="{{ route('words.index') }}" class="btn btn-secondary">გასუფთავება</a>
            @endif

        </form>
    </div>
</div> --}}



{{-- <div class="mb-3">
    <strong>Filter by Greek letter:</strong>
    @foreach($greekAlphabetUpper as $letter)
        @php
            // Preserve current search and type, but replace starts_with with this letter
            $queryParams = request()->except('starts_with');
            $queryParams['starts_with'] = $letter;
        @endphp
        <a href="{{ route('words.index', $queryParams) }}"
            class="btn btn-outline-primary btn-sm {{ request('starts_with') === $letter ? 'active' : '' }}">
            {{ $letter }}
        </a>
    @endforeach
</div> --}}

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- <div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Greek Word</th>
                <th>Present</th>
                <th>Past</th>
                <th>Future</th>
                <th>Georgian Translation</th>
                <th>Type</th>
                @if(Auth::check() && Auth::user()->can_manage_words)
                    <th width="150">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($words as $word)
                <tr>
                    <td class="greek-text fw-bold">{{ $word->greek_word }}</td>
                    <td class="greek-text">{{ $word->greek_present ?? '-' }}</td>
                    <td class="greek-text">{{ $word->greek_past ?? '-' }}</td>
                    <td class="greek-text">{{ $word->greek_future ?? '-' }}</td>
                    <td class="georgian-text fw-bold">{{ $word->georgian_translation }}</td>
                    <td><span class="badge bg-info">{{ $word->word_type }}</span></td>
                    @if(Auth::check() && Auth::user()->can_manage_words)
                        <td>
                            <div class="btn-group btn-group-sm d-flex gap-2" role="group">
                                <a href="{{ route('words.edit', $word) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Edit Word">
                                   Edit
                                </a>
                                <form action="{{ route('words.destroy', $word) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this word: {{ $word->greek_word }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger btn-sm" 
                                            title="Delete Word">
                                            Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ Auth::check() && Auth::user()->can_manage_words ? '7' : '6' }}" class="text-center">
                        No words found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div> --}}

  <livewire:word-list />

  <script>
function speak(text) {
    const synth = window.speechSynthesis;
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'el-GR'; // Greek
    synth.speak(utterance);
}
</script>
@endsection