@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
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
                        <label class="form-label">Word Type</label>
                        <select name="word_type" id="word_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="verb" {{ old('word_type') == 'verb' ? 'selected' : '' }}>Verb</option>
                            <option value="noun" {{ old('word_type') == 'noun' ? 'selected' : '' }}>Noun</option>
                            <option value="adjective" {{ old('word_type') == 'adjective' ? 'selected' : '' }}>Adjective</option>
                            <option value="adverb" {{ old('word_type') == 'adverb' ? 'selected' : '' }}>Adverb</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Georgian Translation</label>
                        <input type="text" name="georgian_translation" class="form-control georgian-text" 
                               value="{{ old('georgian_translation') }}" required>
                    </div>

                    <div class="mb-3" id="english_translation_field" style="display: none;">
                        <label class="form-label">English Translation</label>
                        <input type="text" name="english_translation" class="form-control" 
                               value="{{ old('english_translation') }}">
                    </div>

                    <!-- Non-verb fields -->
                    <div id="simple_fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Present Tense</label>
                            <input type="text" name="greek_present" class="form-control greek-text" 
                                   value="{{ old('greek_present') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Past Tense</label>
                            <input type="text" name="greek_past" class="form-control greek-text" 
                                   value="{{ old('greek_past') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Future Tense</label>
                            <input type="text" name="greek_future" class="form-control greek-text" 
                                   value="{{ old('greek_future') }}">
                        </div>
                    </div>

                    <!-- Verb conjugation fields -->
                    <div id="verb_fields" style="display: none;">
                        <!-- Present Tense -->
                        <div class="card mb-3">
                            <div class="card-header">Present Tense</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Singular (I)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.1st_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.1st_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.1st_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Singular (You)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.2nd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.2nd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.2nd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Singular (He/She/It)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.3rd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.3rd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.3rd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Plural (We)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.1st_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.1st_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[1st_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.1st_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Plural (You plural)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.2nd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.2nd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[2nd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.2nd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Plural (They)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('present_tense.3rd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('present_tense.3rd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="present_tense[3rd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('present_tense.3rd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Past Tense -->
                        <div class="card mb-3">
                            <div class="card-header">Past Tense</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Singular (I)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.1st_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.1st_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.1st_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Singular (You)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.2nd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.2nd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.2nd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Singular (He/She/It)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.3rd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.3rd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.3rd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Plural (We)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.1st_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.1st_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[1st_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.1st_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Plural (You plural)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.2nd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.2nd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[2nd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.2nd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Plural (They)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('past_tense.3rd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('past_tense.3rd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="past_tense[3rd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('past_tense.3rd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Future Tense -->
                        <div class="card mb-3">
                            <div class="card-header">Future Tense</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Singular (I)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.1st_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.1st_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.1st_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Singular (You)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.2nd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.2nd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.2nd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Singular (He/She/It)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_singular][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.3rd_singular.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_singular][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.3rd_singular.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_singular][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.3rd_singular.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">1st Plural (We)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.1st_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.1st_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[1st_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.1st_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">2nd Plural (You plural)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.2nd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.2nd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[2nd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.2nd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">3rd Plural (They)</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_plural][greek]" 
                                                           class="form-control greek-text" placeholder="Greek"
                                                           value="{{ old('future_tense.3rd_plural.greek') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_plural][georgian]" 
                                                           class="form-control georgian-text" placeholder="Georgian"
                                                           value="{{ old('future_tense.3rd_plural.georgian') }}">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="future_tense[3rd_plural][english]" 
                                                           class="form-control" placeholder="English"
                                                           value="{{ old('future_tense.3rd_plural.english') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    const wordTypeSelect = document.getElementById('word_type');
    const verbFields = document.getElementById('verb_fields');
    const simpleFields = document.getElementById('simple_fields');
    const englishTranslationField = document.getElementById('english_translation_field');

    // Handle word type change
    function toggleFields() {
        const selectedType = wordTypeSelect.value;
        
        if (selectedType === 'verb') {
            verbFields.style.display = 'block';
            simpleFields.style.display = 'none';
            englishTranslationField.style.display = 'block';
        } else if (selectedType) {
            verbFields.style.display = 'none';
            simpleFields.style.display = 'block';
            englishTranslationField.style.display = 'none';
        } else {
            verbFields.style.display = 'none';
            simpleFields.style.display = 'none';
            englishTranslationField.style.display = 'none';
        }
    }

    wordTypeSelect.addEventListener('change', toggleFields);
    
    // Initialize on page load
    toggleFields();

    // Autocomplete functionality
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

    // Duplicate check functionality
    greekInput.addEventListener('input', function () {
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
});
</script>

@endsection