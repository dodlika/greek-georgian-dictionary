{{-- resources/views/quiz/results.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-4 mb-3">Quiz Complete!</h1>
                        
                        {{-- Quiz Type Display --}}
                        @php
                            $quizType = $results['quiz_type'] ?? 'vocabulary';
                            
                            if ($quizType === 'vocabulary') {
                                $quizDirection = $results['answers'][0]['quiz_direction'] ?? 'greek_to_georgian';
                                $isGreekToGeorgian = $quizDirection === 'greek_to_georgian';
                                $badgeText = $isGreekToGeorgian ? 'Greek → Georgian' : 'Georgian → Greek';
                                $badgeClass = 'bg-info';
                            } else {
                                // For verb tense quiz, check if it was mixed or specific type
                                $tenseTypes = collect($results['answers'])->pluck('tense_tested')->unique();
                                if ($tenseTypes->count() > 1) {
                                    $badgeText = 'Verb Tense: Mixed';
                                    $badgeClass = 'bg-purple';
                                } elseif ($tenseTypes->first() === 'past') {
                                    $badgeText = 'Verb Tense: Past Only';
                                    $badgeClass = 'bg-warning';
                                } else {
                                    $badgeText = 'Verb Tense: Future Only';
                                    $badgeClass = 'bg-success';
                                }
                            }
                        @endphp
                        
                        <div class="mb-3">
                            <span class="badge {{ $badgeClass }} fs-6">
                                {{ $badgeText }}
                            </span>
                        </div>

                        {{-- Score Display --}}
                        <div class="mb-4">
                            <h2 class="display-1 mb-2 {{ $results['percentage'] >= 80 ? 'text-success' : ($results['percentage'] >= 60 ? 'text-warning' : 'text-danger') }}">
                                {{ $results['percentage'] }}%
                            </h2>
                            <p class="lead">
                                You scored {{ $results['score'] }} out of {{ $results['total'] }}
                            </p>
                            <p class="text-muted">
                                Time taken: {{ $results['time_taken'] }} minutes
                            </p>
                        </div>

                        {{-- Achievement Badge --}}
                        @if($results['is_new_best'])
                        <div class="alert alert-success">
                            <i class="fas fa-trophy"></i> New Personal Best!
                        </div>
                        @endif
                    </div>

                    {{-- Detailed Results --}}
                    <div class="mb-4">
                        <h4 class="mb-3">Review Your Answers</h4>
                        <div class="row">
                            @foreach($results['answers'] as $index => $answer)
                            <div class="col-md-6 mb-3">
                                <div class="card {{ $answer['is_correct'] ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge {{ $answer['is_correct'] ? 'bg-success' : 'bg-danger' }}">
                                                {{ $index + 1 }}
                                            </span>
                                            <i class="fas {{ $answer['is_correct'] ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                        </div>
                                        
                                        @if($quizType === 'vocabulary')
                                            {{-- Vocabulary Quiz Result --}}
                                            <div class="mb-2">
                                                <strong>Question:</strong>
                                                <span class="text-primary">{{ $answer['question_word'] }}</span>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <strong>Your answer:</strong>
                                                <span class="{{ $answer['is_correct'] ? 'text-success' : 'text-danger' }}">
                                                    {{ $answer['user_answer'] }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <strong>Correct answer:</strong>
                                                <span class="text-success">{{ $answer['correct_answer'] }}</span>
                                            </div>
                                        @else
                                            {{-- Verb Tense Quiz Result --}}
                                            <div class="mb-2">
                                                <strong>Base Verb:</strong>
                                                <span class="text-primary" style="font-family: 'Times New Roman', serif;">
                                                    {{ $answer['question_word'] }}
                                                </span>
                                                @if(!empty($answer['georgian_translation']))
                                                    <small class="text-muted d-block">{{ $answer['georgian_translation'] }}</small>
                                                @endif
                                            </div>
                                            
                                            <div class="mb-2">
                                                <strong>Required:</strong>
                                                <span class="badge {{ $answer['tense_tested'] === 'past' ? 'bg-warning' : 'bg-success' }} text-white">
                                                    {{ ucfirst($answer['tense_tested']) }} Tense
                                                </span>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <strong>Your answer:</strong>
                                                <span class="{{ $answer['is_correct'] ? 'text-success' : 'text-danger' }}" style="font-family: 'Times New Roman', serif;">
                                                    {{ $answer['user_answer'] }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <strong>Correct answer:</strong>
                                                <span class="text-success" style="font-family: 'Times New Roman', serif;">
                                                    {{ $answer['correct_answer'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="text-center">
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('quiz.index') }}" class="btn btn-primary">
                                Take Another Quiz
                            </a>
                            <a href="{{ route('quiz.leaderboard') }}" class="btn btn-success">
                                View Leaderboard
                            </a>
                            @if(collect($results['answers'])->where('is_correct', false)->count() > 0)
                            <form action="{{ route('quiz.saveIncorrectToFavorites') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    Save Incorrect Words to Favorites
                                </button>
                            </form>
                            @endif
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

<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>

@endsection