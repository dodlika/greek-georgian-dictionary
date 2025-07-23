@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add New Word</div>
            <div class="card-body">
                @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
                <form method="POST" action="{{ route('words.store') }}">
                    @csrf
                  <div class="mb-3">
    <label class="form-label">Greek Word</label>
<input type="text"
       name="greek_word"
       id="greek_word"
       list="greekSuggestions"
       class="form-control greek-text @error('greek_word') is-invalid @enderror"
       value="{{ old('greek_word') }}"
       autocomplete="off"
       required>
<datalist id="greekSuggestions"></datalist>
@error('greek_word')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
<p id="duplicate-warning" style="color: red; display: none;">⚠️ This Greek word already exists.</p>

</div>

                    
                    <div class="mb-3">
                        <label class="form-label">Present Tense</label>
                        <input type="text" name="greek_present" class="form-control greek-text">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Past Tense</label>
                        <input type="text" name="greek_past" class="form-control greek-text">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Future Tense</label>
                        <input type="text" name="greek_future" class="form-control greek-text">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Georgian Translation</label>
                        <input type="text" name="georgian_translation" class="form-control georgian-text" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Word Type</label>
                        <select name="word_type" class="form-control" required>
                            <option value="verb">Verb</option>
                            <option value="noun">Noun</option>
                            <option value="adjective">Adjective</option>
                            <option value="adverb">Adverb</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Word</button>
                    <a href="{{ route('words.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
                
            </div>
        </div>
    </div>
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

document.getElementById('greek_word').addEventListener('input', function () {
    const value = this.value;

    if (value.length < 1) {
        document.getElementById('duplicate-warning').style.display = 'none';
        return;
    }

    fetch(`/words/check-duplicate?greek_word=${encodeURIComponent(value)}`)
        .then(response => response.json())
        .then(data => {
            const warning = document.getElementById('duplicate-warning');
            const submitBtn = document.querySelector('form button[type="submit"]');

            if (data.exists) {
                warning.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                warning.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
});
</script>



@endsection