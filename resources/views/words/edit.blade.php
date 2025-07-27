@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Edit Word</div>
                <div class="card-body">
                    <form action="{{ route('words.update', $word) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="greek_word" class="form-label">Greek Word</label>
                            <input type="text" 
                                   value="{{ old('greek_word', $word->greek_word) }}" 
                                   name="greek_word" 
                                   id="greek_word" 
                                   class="form-control greek-text" 
                                   list="greekSuggestions" 
                                   autocomplete="off" 
                                   required>
                            <datalist id="greekSuggestions"></datalist>
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

                        <div class="mb-3">
                            <label for="georgian_translation" class="form-label">Georgian Translation</label>
                            <input type="text" 
                                   name="georgian_translation" 
                                   id="georgian_translation" 
                                   class="form-control georgian-text"
                                   value="{{ old('georgian_translation', $word->georgian_translation) }}" 
                                   required>
                        </div>

                        <div class="mb-3" id="english_translation_field" style="{{ $word->word_type === 'verb' ? '' : 'display: none;' }}">
                            <label for="english_translation" class="form-label">English Translation</label>
                            <input type="text" 
                                   name="english_translation" 
                                   id="english_translation" 
                                   class="form-control"
                                   value="{{ old('english_translation', $word->english_translation ?? '') }}">
                        </div>

                        <!-- Non-verb fields -->
                        <div id="simple_fields" style="{{ $word->word_type !== 'verb' ? '' : 'display: none;' }}">
                            <div class="mb-3">
                                <label for="greek_present" class="form-label">Present</label>
                                <input type="text" 
                                       name="greek_present" 
                                       id="greek_present" 
                                       class="form-control greek-text"
                                       value="{{ old('greek_present', $word->greek_present) }}">
                            </div>

                            <div class="mb-3">
                                <label for="greek_past" class="form-label">Past</label>
                                <input type="text" 
                                       name="greek_past" 
                                       id="greek_past" 
                                       class="form-control greek-text"
                                       value="{{ old('greek_past', $word->greek_past) }}">
                            </div>

                            <div class="mb-3">
                                <label for="greek_future" class="form-label">Future</label>
                                <input type="text" 
                                       name="greek_future" 
                                       id="greek_future" 
                                       class="form-control greek-text"
                                       value="{{ old('greek_future', $word->greek_future) }}">
                            </div>
                        </div>

                        <!-- Verb conjugation fields -->
                        <div id="verb_fields" style="{{ $word->word_type === 'verb' ? '' : 'display: none;' }}">
                            @php
                                $presentTense = is_array($word->present_tense) ? $word->present_tense : (is_string($word->present_tense) ? json_decode($word->present_tense, true) : []);
                                $pastTense = is_array($word->past_tense) ? $word->past_tense : (is_string($word->past_tense) ? json_decode($word->past_tense, true) : []);
                                $futureTense = is_array($word->future_tense) ? $word->future_tense : (is_string($word->future_tense) ? json_decode($word->future_tense, true) : []);
                            @endphp

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
                                                               value="{{ old('present_tense.1st_singular.greek', $presentTense['1st_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[1st_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.1st_singular.georgian', $presentTense['1st_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[1st_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.1st_singular.english', $presentTense['1st_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Singular (You)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('present_tense.2nd_singular.greek', $presentTense['2nd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.2nd_singular.georgian', $presentTense['2nd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.2nd_singular.english', $presentTense['2nd_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Singular (He/She/It)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('present_tense.3rd_singular.greek', $presentTense['3rd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.3rd_singular.georgian', $presentTense['3rd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.3rd_singular.english', $presentTense['3rd_singular']['english'] ?? '') }}">
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
                                                               value="{{ old('present_tense.1st_plural.greek', $presentTense['1st_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[1st_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.1st_plural.georgian', $presentTense['1st_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[1st_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.1st_plural.english', $presentTense['1st_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Plural (You plural)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('present_tense.2nd_plural.greek', $presentTense['2nd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.2nd_plural.georgian', $presentTense['2nd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[2nd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.2nd_plural.english', $presentTense['2nd_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Plural (They)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('present_tense.3rd_plural.greek', $presentTense['3rd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('present_tense.3rd_plural.georgian', $presentTense['3rd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="present_tense[3rd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('present_tense.3rd_plural.english', $presentTense['3rd_plural']['english'] ?? '') }}">
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
                                                               value="{{ old('past_tense.1st_singular.greek', $pastTense['1st_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[1st_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.1st_singular.georgian', $pastTense['1st_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[1st_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.1st_singular.english', $pastTense['1st_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Singular (You)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('past_tense.2nd_singular.greek', $pastTense['2nd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.2nd_singular.georgian', $pastTense['2nd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.2nd_singular.english', $pastTense['2nd_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Singular (He/She/It)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('past_tense.3rd_singular.greek', $pastTense['3rd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.3rd_singular.georgian', $pastTense['3rd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.3rd_singular.english', $pastTense['3rd_singular']['english'] ?? '') }}">
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
                                                               value="{{ old('past_tense.1st_plural.greek', $pastTense['1st_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[1st_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.1st_plural.georgian', $pastTense['1st_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[1st_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.1st_plural.english', $pastTense['1st_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Plural (You plural)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('past_tense.2nd_plural.greek', $pastTense['2nd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.2nd_plural.georgian', $pastTense['2nd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[2nd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.2nd_plural.english', $pastTense['2nd_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Plural (They)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('past_tense.3rd_plural.greek', $pastTense['3rd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('past_tense.3rd_plural.georgian', $pastTense['3rd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="past_tense[3rd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('past_tense.3rd_plural.english', $pastTense['3rd_plural']['english'] ?? '') }}">
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
                                                               value="{{ old('future_tense.1st_singular.greek', $futureTense['1st_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[1st_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.1st_singular.georgian', $futureTense['1st_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[1st_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.1st_singular.english', $futureTense['1st_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Singular (You)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('future_tense.2nd_singular.greek', $futureTense['2nd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.2nd_singular.georgian', $futureTense['2nd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.2nd_singular.english', $futureTense['2nd_singular']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Singular (He/She/It)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_singular][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('future_tense.3rd_singular.greek', $futureTense['3rd_singular']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_singular][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.3rd_singular.georgian', $futureTense['3rd_singular']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_singular][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.3rd_singular.english', $futureTense['3rd_singular']['english'] ?? '') }}">
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
                                                               value="{{ old('future_tense.1st_plural.greek', $futureTense['1st_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[1st_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.1st_plural.georgian', $futureTense['1st_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[1st_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.1st_plural.english', $futureTense['1st_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">2nd Plural (You plural)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('future_tense.2nd_plural.greek', $futureTense['2nd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.2nd_plural.georgian', $futureTense['2nd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[2nd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.2nd_plural.english', $futureTense['2nd_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">3rd Plural (They)</label>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_plural][greek]" 
                                                               class="form-control greek-text" placeholder="Greek"
                                                               value="{{ old('future_tense.3rd_plural.greek', $futureTense['3rd_plural']['greek'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_plural][georgian]" 
                                                               class="form-control georgian-text" placeholder="Georgian"
                                                               value="{{ old('future_tense.3rd_plural.georgian', $futureTense['3rd_plural']['georgian'] ?? '') }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" name="future_tense[3rd_plural][english]" 
                                                               class="form-control" placeholder="English"
                                                               value="{{ old('future_tense.3rd_plural.english', $futureTense['3rd_plural']['english'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Word</button>
                        <a href="{{ route('words.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
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
});
</script>

@endsection