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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Question {{ $quizData['current_question'] + 1 }} of {{ $quizData['total_questions'] }}</span>
                            <span class="text-muted">Score: {{ $quizData['score'] }}/{{ $quizData['current_question'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Quiz Type Indicator --}}
                    @php
                        $quizType = $quizData['quiz_type'] ?? 'vocabulary';
                        
                        if ($quizType === 'vocabulary') {
                            $quizDirection = $quizData['quiz_direction'] ?? 'greek_to_georgian';
                            $isGreekToGeorgian = $quizDirection === 'greek_to_georgian';
                            $badgeText = $isGreekToGeorgian ? 'Greek → Georgian' : 'Georgian → Greek';
                            $badgeClass = 'bg-info';
                        } else {
                            $verbTenseType = $quizData['verb_tense_type'] ?? 'mixed';
                            if ($verbTenseType === 'past') {
                                $badgeText = 'Verb Tense: Past Only';
                                $badgeClass = 'bg-warning';
                            } elseif ($verbTenseType === 'future') {
                                $badgeText = 'Verb Tense: Future Only';
                                $badgeClass = 'bg-success';
                            } else {
                                $badgeText = 'Verb Tense: Mixed';
                                $badgeClass = 'bg-purple';
                            }
                        }
                    @endphp
                    
                    <div class="text-center mb-3">
                        <small class="badge {{ $badgeClass }}">
                            {{ $badgeText }}
                        </small>
                    </div>

                    {{-- Question Content --}}
                    @if($quizType === 'vocabulary')
                        {{-- Vocabulary Quiz Question --}}
                        <div class="text-center mb-4">
                            <h2 class="display-4 mb-3" style="font-family: 'Times New Roman', serif;">
                                {{ $isGreekToGeorgian ? $currentWord['greek_word'] : $currentWord['georgian_translation'] }}
                            </h2>
                            <p class="text-muted">
                                {{ $isGreekToGeorgian ? 'Enter the Georgian translation:' : 'Enter the Greek translation:' }}
                            </p>
                        </div>

                        {{-- Answer Form --}}
                        <form action="{{ route('quiz.answer') }}" method="POST" class="text-center">
                            @csrf
                            <div class="mb-4">
                                <input 
                                    type="text" 
                                    name="answer" 
                                    class="form-control form-control-lg text-center" 
                                    placeholder="{{ $isGreekToGeorgian ? 'Georgian translation...' : 'Greek translation...' }}" 
                                    required 
                                    autofocus
                                    autocomplete="off"
                                >
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Submit Answer
                            </button>
                        </form>
                    @else
                        {{-- Verb Tense Quiz Question --}}
                        @php
                            $verbTenseType = $quizData['verb_tense_type'] ?? 'mixed';
                            
                            // Determine which tense to test for this question
                            if ($verbTenseType === 'mixed') {
                                $tenseToTest = rand(0, 1) ? 'past' : 'future';
                            } else {
                                $tenseToTest = $verbTenseType;
                            }
                            
                            $tenseLabel = $tenseToTest === 'past' ? 'Past Tense' : 'Future Tense';
                            $tenseColor = $tenseToTest === 'past' ? 'text-warning' : 'text-success';
                        @endphp
                        
                        <div class="text-center mb-4">
                            {{-- Present the base verb --}}
                            <h2 class="display-4 mb-3" style="font-family: 'Times New Roman', serif;">
                                {{ $currentWord['greek_word'] }}
                            </h2>
                            
                            {{-- Show Georgian translation for context --}}
                            <p class="text-muted mb-2">
                                <em>({{ $currentWord['georgian_translation'] ?? '' }})</em>
                            </p>
                            
                            {{-- Tense instruction --}}
                            <div class="mb-3">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    Fill in the <strong class="{{ $tenseColor }}">{{ $tenseLabel }}</strong>
                                </span>
                            </div>
                            
                            <p class="text-muted">
                                Enter the {{ strtolower($tenseLabel) }} form of this verb:
                            </p>
                        </div>

                        {{-- Answer Form --}}
                        <form action="{{ route('quiz.answer') }}" method="POST" class="text-center">
                            @csrf
                            <div class="mb-4">
                                <input 
                                    type="text" 
                                    name="answer" 
                                    class="form-control form-control-lg text-center" 
                                    placeholder="Enter {{ strtolower($tenseLabel) }} form..."
                                    required 
                                    autofocus
                                    autocomplete="off"
                                    style="font-family: 'Times New Roman', serif;"
                                >
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Submit Answer
                            </button>
                        </form>
                    @endif

                    {{-- Navigation --}}
                    <div class="text-center mt-4">
                        <form id="quitQuizForm" action="{{ route('quiz.abort') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="quitQuiz()">
                                Quit Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>

<script>
// Auto-focus on the input field and handle Enter key
document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="answer"]');
    if (input) {
        input.focus();
    }
});

function quitQuiz() {
    if (confirm('Are you sure you want to quit this quiz? Your progress will be lost.')) {
        document.getElementById('quitQuizForm').submit();
    }
}
</script>
@endsection