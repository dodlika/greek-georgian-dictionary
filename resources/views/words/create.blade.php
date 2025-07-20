@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add New Word</div>
            <div class="card-body">
                <form method="POST" action="{{ route('words.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Greek Word</label>
                        <input type="text" name="greek_word" class="form-control greek-text" required>
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
@endsection