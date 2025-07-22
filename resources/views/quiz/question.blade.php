{{-- resources/views/quiz/question.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Progress Bar --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">
                                Question {{ $quizData['current_question'] + 1 }} of {{ $quizData['total_questions'] }}
                            </span>
                            <span class="text-muted small">
                                Score: {{ $quizData['score'] }}/{{ $quizData['current_question'] }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Question --}}
                    <div class="text-center mb-5">
                        <h2 class="h5 mb-3">Translate this Greek word:</h2>
                        <div class="bg-light p-4 rounded mb-3">
                            <div class="display-4 fw-bold text-primary" style="font-family: 'Times New Roman', serif;">
                                {{ $currentWord['greek_word'] }}
                            </div>
                        </div>
                        <p class="text-muted small">
                            Please provide the Georgian translation for the word above.
                        </p>
                    </div>

                    {{-- Answer Form --}}
                    <form action="{{ route('quiz.answer') }}" method="POST" id="answerForm">
                        @csrf
                        <div class="mb-4">
                            <label for="answer" class="form-label">Your Answer (in Georgian):</label>
                            <input type="text"
                                   name="answer"
                                   id="answer"
                                   required
                                   autocomplete="off"
                                   class="form-control form-control-lg"
                                   placeholder="Enter Georgian translation...">
                        </div>

                        <div class="d-flex justify-content-between">
                            {{-- Abort Quiz --}}
                            <button type="button" class="btn btn-danger" onclick="abortQuiz()">
                                Abort Quiz
                            </button>

                            {{-- Submit Answer --}}
                            <button type="submit" class="btn btn-primary btn-lg">
                                Submit Answer
                            </button>
                        </div>
                    </form>

                    {{-- Hidden Abort Form --}}
                    <form action="{{ route('quiz.abort') }}" method="POST" id="abortForm" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Focus input
document.getElementById('answer').focus();

// Handle Enter key
document.getElementById('answer').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('answerForm').submit();
    }
});

// Abort quiz function
function abortQuiz() {
    if (confirm('Are you sure you want to abort this quiz? Your progress will be lost.')) {
        document.getElementById('abortForm').submit();
    }
}
</script>
@endsection