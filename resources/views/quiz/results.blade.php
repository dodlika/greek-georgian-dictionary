{{-- resources/views/quiz/results.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Results Header --}}
                    <div class="text-center mb-5">
                        <h1 class="card-title h2 mb-3">Quiz Complete!</h1>
                        
                        {{-- Score Display --}}
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-8">
                                <div class="alert alert-{{ $results['percentage'] >= 80 ? 'success' : ($results['percentage'] >= 60 ? 'warning' : 'danger') }} p-4">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h2 class="display-4 mb-0">{{ $results['percentage'] }}%</h2>
                                            <p class="mb-0">Final Score</p>
                                        </div>
                                        <div class="col-md-4">
                                            <h3 class="mb-0">{{ $results['score'] }}/{{ $results['total'] }}</h3>
                                            <p class="mb-0">Correct Answers</p>
                                        </div>
                                        <div class="col-md-4">
                                            <h4 class="mb-0">{{ $results['time_taken'] }} min</h4>
                                            <p class="mb-0">Time Taken</p>
                                        </div>
                                    </div>
                                    
                                    @if($results['is_new_best'])
                                    <div class="mt-3">
                                        <span class="badge bg-warning text-dark">🎉 New Personal Best!</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Quiz Type Badge --}}
                        @php
                            $quizType = $results['quiz_type'] ?? 'vocabulary';
                            if ($quizType === 'vocabulary') {
                                $badgeText = 'Vocabulary Quiz';
                                $badgeClass = 'bg-info';
                            } else {
                                $badgeText = 'Verb Conjugation Quiz';
                                $badgeClass = 'bg-purple';
                            }
                        @endphp
                        
                        <div class="mb-4">
                            <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">{{ $badgeText }}</span>
                        </div>
                    </div>

                    {{-- Detailed Results --}}
                    @if(count($results['answers']) > 0)
                    <div class="mb-5">
                        <h3 class="h4 mb-4">Review Your Answers</h3>
                        
                        <div class="accordion" id="answersAccordion">
                            @foreach($results['answers'] as $index => $answer)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $answer['is_correct'] ? 'bg-light-success' : 'bg-light-danger' }} collapsed" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $index }}" 
                                            aria-expanded="false" 
                                            aria-controls="collapse{{ $index }}">
                                        <div class="d-flex align-items-center w-100">
                                            <span class="badge {{ $answer['is_correct'] ? 'bg-success' : 'bg-danger' }} me-3">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="flex-grow-1">
                                                <strong>{{ $answer['question_word'] }}</strong>
                                                @if($answer['quiz_type'] === 'verb_tense')
                                                    - {{ ucfirst($answer['tense_tested']) }} tense, {{ str_replace('_', ' ', $answer['person_tested']) }}
                                                @endif
                                            </div>
                                            <span class="ms-auto me-3">
                                                @if($answer['is_correct'])
                                                    <i class="text-success">✓</i>
                                                @else
                                                    <i class="text-danger">✗</i>
                                                @endif
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" 
                                     class="accordion-collapse collapse" 
                                     aria-labelledby="heading{{ $index }}" 
                                     data-bs-parent="#answersAccordion">
                                    <div class="accordion-body">
                                        @if($answer['quiz_type'] === 'vocabulary')
                                            {{-- Vocabulary Quiz Answer Details --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Question:</strong>
                                                    <p class="mb-2" style="font-family: 'Times New Roman', serif;">
                                                        {{ $answer['question_word'] }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Direction:</strong>
                                                    <p class="mb-2">
                                                        {{ $answer['quiz_direction'] === 'greek_to_georgian' ? 'Greek → Georgian' : 'Georgian → Greek' }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <strong>Your Answer:</strong>
                                                    <p class="mb-2 {{ $answer['is_correct'] ? 'text-success' : 'text-danger' }}">
                                                        {{ $answer['user_answer'] }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Correct Answer:</strong>
                                                    <p class="mb-2 text-success">
                                                        {{ $answer['correct_answer'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Verb Conjugation Quiz Answer Details --}}
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <strong>Present Form (Given):</strong>
                                                    <p class="mb-2" style="font-family: 'Times New Roman', serif;">
                                                        {{ $answer['present_form'] ?? $answer['question_word'] }}
                                                        @if(isset($answer['georgian_translation']) && $answer['georgian_translation'])
                                                            <em class="text-muted">({{ $answer['georgian_translation'] }})</em>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Required:</strong>
                                                    <p class="mb-2">
                                                        <span class="badge bg-secondary">Present → {{ ucfirst($answer['tense_tested']) }}</span>
                                                        <span class="badge bg-outline-secondary ms-2">1st Person Singular</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Status:</strong>
                                                    <p class="mb-2">
                                                        <span class="badge {{ $answer['is_correct'] ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $answer['is_correct'] ? 'Correct' : 'Incorrect' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <strong>Your Answer:</strong>
                                                    <p class="mb-2 {{ $answer['is_correct'] ? 'text-success' : 'text-danger' }}" style="font-family: 'Times New Roman', serif;">
                                                        {{ $answer['user_answer'] }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Correct Answer:</strong>
                                                    <p class="mb-2 text-success" style="font-family: 'Times New Roman', serif;">
                                                        {{ $answer['correct_answer'] }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            @if(isset($answer['correct_georgian']) || isset($answer['correct_english']))
                                            <div class="row mt-2">
                                                @if(isset($answer['correct_georgian']))
                                                <div class="col-md-6">
                                                    <strong>Georgian:</strong>
                                                    <p class="mb-2 text-muted">{{ $answer['correct_georgian'] }}</p>
                                                </div>
                                                @endif
                                                @if(isset($answer['correct_english']))
                                                <div class="col-md-6">
                                                    <strong>English:</strong>
                                                    <p class="mb-2 text-muted">{{ $answer['correct_english'] }}</p>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    <a href="{{ route('quiz.index') }}" class="btn btn-primary">
                                        Take Another Quiz
                                    </a>
                                    
                                    @if(Session::has('quiz_incorrect_words') && count(Session::get('quiz_incorrect_words')) > 0)
                                    <form action="{{ route('quiz.saveIncorrectToFavorites') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            Save Incorrect Words to Favorites
                                        </button>
                                    </form>
                                    @endif
                                    
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
        </div>
    </div>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    
    .bg-light-success {
        background-color: #d1edff !important;
    }
    
    .bg-light-danger {
        background-color: #f8d7da !important;
    }
    
    .bg-outline-secondary {
        background-color: transparent !important;
        border: 1px solid #6c757d !important;
        color: #6c757d !important;
    }
</style>

@endsection