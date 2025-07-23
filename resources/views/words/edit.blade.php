@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Word</h2>

    <form action="{{ route('words.update', $word) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="greek_word" class="form-label">Greek Word</label>
           
                   <input type="text"  value="{{ old('greek_word', $word->greek_word) }} "name="greek_word" id="greek_word" class="form-control" list="greekSuggestions" autocomplete="off" required >
<datalist id="greekSuggestions"></datalist>
        </div>

        <div class="mb-3">
            <label for="greek_present" class="form-label">Present</label>
            <input type="text" name="greek_present" id="greek_present" class="form-control" 
                   value="{{ old('greek_present', $word->greek_present) }}">
        </div>

        <div class="mb-3">
            <label for="greek_past" class="form-label">Past</label>
            <input type="text" name="greek_past" id="greek_past" class="form-control" 
                   value="{{ old('greek_past', $word->greek_past) }}">
        </div>

        <div class="mb-3">
            <label for="greek_future" class="form-label">Future</label>
            <input type="text" name="greek_future" id="greek_future" class="form-control" 
                   value="{{ old('greek_future', $word->greek_future) }}">
        </div>

        <div class="mb-3">
            <label for="georgian_translation" class="form-label">Georgian Translation</label>
            <input type="text" name="georgian_translation" id="georgian_translation" class="form-control" 
                   value="{{ old('georgian_translation', $word->georgian_translation) }}" required>
        </div>

        <div class="mb-3">
            <label for="word_type" class="form-label">Word Type</label>
            <select name="word_type" id="word_type" class="form-select" required>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ (old('word_type', $word->word_type) == $type) ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Word</button>
        <a href="{{ route('words.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const greekInput = document.getElementById('greek_word');
    const datalist = document.getElementById('greekSuggestions');

    greekInput.addEventListener('input', function () {
        const query = greekInput.value;

        if (query.length < 2) {
            datalist.innerHTML = '';
            return;
        }

        fetch(`/words/autocomplete?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                datalist.innerHTML = '';
                data.forEach(word => {
                    const option = document.createElement('option');
                    option.value = word;
                    datalist.appendChild(option);
                });
            });
    });
});
</script>



@endsection
