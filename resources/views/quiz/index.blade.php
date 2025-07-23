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
                        <p class="text-muted">
                            Test your Greek vocabulary knowledge! You'll see Greek words and need to provide the Georgian translation.
                        </p>
                        <p class="small text-muted">
                            <strong>Total words available: {{ $totalWords }}</strong>
                        </p>

                        <form action="{{ route('quiz.start') }}" method="POST" id="quizForm">
    @csrf
    <div class="mb-3">
        <label for="word_count" class="form-label">How many words do you want to be tested on?</label>
        <select name="word_count" id="word_count" class="form-select w-auto">
            <option value="10">10 words</option>
            <option value="20" selected>20 words</option>
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
    function handleQuizSubmit(event) {
        const addedAfter = document.getElementById('added_after').value;
        const form = event.target.closest('form');

        if (addedAfter) {
            event.preventDefault();

            fetch(`{{ route('quiz.wordCount') }}?added_after=${addedAfter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.word_count < 5) {
                        if (confirm(`Only ${data.word_count} word(s) found for the selected date. Do you want to continue?`)) {
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
