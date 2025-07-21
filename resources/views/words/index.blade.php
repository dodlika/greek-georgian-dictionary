@extends('layouts.app')

@section('content')


<div class="row my-4">
   <select name="word_type" class="form-select me-2" style="max-width: 180px;">
    <option value="">ყველა ტიპი</option>
    <option value="noun" {{ request('word_type') == 'noun' ? 'selected' : '' }}>ზმნა</option>
    <option value="verb" {{ request('word_type') == 'verb' ? 'selected' : '' }}>საკითხავი</option>
    <option value="adjective" {{ request('word_type') == 'adjective' ? 'selected' : '' }}>სახელობითი</option>
</select>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('words.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2 georgian-text" 
                   placeholder="ძიება ქართულად..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">ძიება</button>
            @if(request('search'))
                <a href="{{ route('words.index') }}" class="btn btn-secondary ms-2">გასუფთავება</a>
            @endif
        </form>
    </div>
    <div class="col-md-4">
        <a href="{{ route('words.create') }}" class="btn btn-success">Add New Word</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="mb-3">
    <strong>Filter by Greek letter:</strong>
    @foreach($greekLetters as $letter)
        <a href="{{ route('words.index', ['starts_with' => $letter]) }}" class="btn btn-outline-primary btn-sm">
            {{ $letter }}
        </a>
    @endforeach
</div>



<div class="table-responsive">
    <table class="table table-striped">
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
            @forelse($words as $word)
                <tr>
                    <td class="greek-text fw-bold">{{ $word->greek_word }}</td>
                    <td class="greek-text">{{ $word->greek_present ?? '-' }}</td>
                    <td class="greek-text">{{ $word->greek_past ?? '-' }}</td>
                    <td class="greek-text">{{ $word->greek_future ?? '-' }}</td>
                    <td class="georgian-text fw-bold">{{ $word->georgian_translation }}</td>
                    <td><span class="badge bg-info">{{ $word->word_type }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No words found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $words->withQueryString()->links() }}
@endsection