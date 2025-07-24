{{-- resources/views/quiz/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4">Greek Vocabulary Quiz</h1>

                    {{-- User Stats --}}
                    @if($user->hasQuizHistory())
                    <div class="alert alert-primary mb-4">
                        <h5 class="alert-heading mb-3">Your Quiz Statistics</h5>
                        <div class="row text-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h2 class="text-primary">{{ $user->best_percentage }}%</h2>
                                <small class="text-muted">Best Score</small><br>
                                <small class="text-muted">({{ $user->best_quiz_score }}/{{ $user->best_quiz_total }})</small>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h2 class="text-success">{{ $user->total_quizzes_taken }}</h2>
                                <small class="text-muted">Quizzes Taken</small>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-purple">
                                    {{ $user->best_quiz_date ? $user->best_quiz_date->format('M j, Y') : 'N/A' }}
                                </h5>
                                <small class="text-muted">Best Score Date</small>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Quiz Setup --}}
                    <div class="bg-light p-4 rounded mb-4">
                        <h5 class="mb-3">Start New Quiz</h5>
                        
                        <form action="{{ route('quiz.start') }}" method="POST" id="quizForm">
                            @csrf
                            
                            {{-- Quiz Type Selection --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Quiz Type</label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="quiz_type" id="vocabulary_quiz" value="vocabulary" checked onchange="updateQuizOptions()">
                                                    <label class="form-check-label" for="vocabulary_quiz">
                                                        <strong>Vocabulary Quiz</strong>
                                                    </label>
                                                </div>
                                                <p class="small text-muted mt-2 mb-0" id="vocabulary-description">
                                                    Test your Greek vocabulary knowledge! Translate between Greek and Georgian.
                                                </p>
                                                <p class="small text-muted">
                                                    <strong>Available words: {{ $totalWords }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="quiz_type" id="verb_tense_quiz" value="verb_tense" onchange="updateQuizOptions()">
                                                    <label class="form-check-label" for="verb_tense_quiz">
                                                        <strong>Verb Tense Quiz</strong>
                                                    </label>
                                                </div>
                                                <p class="small text-muted mt-2 mb-0">
                                                    Practice Greek verb conjugations! Fill in past and future tenses.
                                                </p>
                                                <p class="small text-muted">
                                                    <strong>Available verbs: {{ $totalVerbs }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Vocabulary Quiz Options --}}
                            <div id="vocabulary-options">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Quiz Direction</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="quiz_direction" id="greek_to_georgian" value="greek_to_georgian" checked onchange="updateQuizDescription()">
                                                <label class="form-check-label" for="greek_to_georgian">
                                                    <strong>Greek → Georgian</strong>
                                                    <small class="d-block text-muted">See Greek words, type Georgian translation</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="quiz_direction" id="georgian_to_greek" value="georgian_to_greek" onchange="updateQuizDescription()">
                                                <label class="form-check-label" for="georgian_to_greek">
                                                    <strong>Georgian → Greek</strong>
                                                    <small class="d-block text-muted">See Georgian words, type Greek translation</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Verb Tense Quiz Options --}}
                            <div id="verb-tense-options" style="display: none;">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tense Type</label>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="verb_tense_type" id="past_tense" value="past">
                                                <label class="form-check-label" for="past_tense">
                                                    <strong>Past Tense Only</strong>
                                                    <small class="d-block text-muted">Fill in past tense forms</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="verb_tense_type" id="future_tense" value="future">
                                                <label class="form-check-label" for="future_tense">
                                                    <strong>Future Tense Only</strong>
                                                    <small class="d-block text-muted">Fill in future tense forms</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="verb_tense_type" id="mixed_tense" value="mixed" checked>
                                                <label class="form-check-label" for="mixed_tense">
                                                    <strong>Mixed</strong>
                                                    <small class="d-block text-muted">Random past/future</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="word_count" class="form-label">How many words do you want to be tested on?</label>
                                <select name="word_count" id="word_count" class="form-select w-auto">
                                    <option value="10" selected>10 words</option>
                                    <option value="20">20 words</option>
                                    <option value="30">30 words</option>
                                    <option value="50">50 words</option>
                                    <option value="100">100 words</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="added_after" class="form-label">Only include words added on or after:</label>
                                <input type="date" id="added_after" name="added_after" class="form-control w-auto">
                            </div>

                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <input type="hidden" name="force_start" id="force_start" value="0">

                            <button type="submit" class="btn btn-primary" onclick="handleQuizSubmit(event)">
                                Start Quiz
                            </button>
                        </form>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('quiz.leaderboard') }}" class="btn btn-success">
                            View Leaderboard
                        </a>
                        <a href="{{ route('words.index') }}" class="btn btn-secondary">
                            Browse Words
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQuizOptions() {
        const vocabularyQuiz = document.getElementById('vocabulary_quiz').checked;
        const vocabularyOptions = document.getElementById('vocabulary-options');
        const verbTenseOptions = document.getElementById('verb-tense-options');
        
        if (vocabularyQuiz) {
            vocabularyOptions.style.display = 'block';
            verbTenseOptions.style.display = 'none';
        } else {
            vocabularyOptions.style.display = 'none';
            verbTenseOptions.style.display = 'block';
        }
        
        updateQuizDescription();
    }

    function updateQuizDescription() {
        const vocabularyQuiz = document.getElementById('vocabulary_quiz').checked;
        const description = document.getElementById('vocabulary-description');
        
        if (vocabularyQuiz) {
            const greekToGeorgian = document.getElementById('greek_to_georgian').checked;
            if (greekToGeorgian) {
                description.textContent = "Test your Greek vocabulary knowledge! You'll see Greek words and need to provide the Georgian translation.";
            } else {
                description.textContent = "Test your Georgian vocabulary knowledge! You'll see Georgian words and need to provide the Greek translation.";
            }
        }
    }

    function handleQuizSubmit(event) {
        const addedAfter = document.getElementById('added_after').value;
        const form = event.target.closest('form');
        const quizType = document.querySelector('input[name="quiz_type"]:checked').value;

        if (addedAfter) {
            event.preventDefault();

            const url = `{{ route('quiz.wordCount') }}?added_after=${addedAfter}&quiz_type=${quizType}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.word_count < 5) {
                        const wordType = quizType === 'verb_tense' ? 'verb(s)' : 'word(s)';
                        if (confirm(`Only ${data.word_count} ${wordType} found for the selected date. Do you want to continue?`)) {
                            document.getElementById('force_start').value = 1;
                            form.submit();
                        }
                    } else {
                        form.submit();
                    }
                }).catch(err => {
                    alert('Failed to check word count. Please try again.');
                    console.error(err);
                });
        }
    }
</script>

@endsection