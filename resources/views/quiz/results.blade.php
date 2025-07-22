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
                        <h1 class="h3 fw-bold mb-4">Quiz Complete!</h1>

                        @php
                            $bgColor = $results['percentage'] >= 80 ? 'success' : ($results['percentage'] >= 60 ? 'warning' : 'danger');
                            $textColor = $bgColor;
                        @endphp

                        <div class="p-4 rounded bg-{{ $bgColor }} bg-opacity-25 mb-4">
                            <div class="display-4 fw-bold text-{{ $textColor }} mb-2">
                                {{ $results['percentage'] }}%
                            </div>
                            <div class="fs-5 text-muted">
                                {{ $results['score'] }} out of {{ $results['total'] }} correct
                            </div>

                            @if($results['is_new_best'])
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark fs-6 p-2">
                                    🎉 New Personal Best!
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="row g-3 justify-content-center mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                    <div class="h4 fw-bold text-primary">{{ $results['time_taken'] }} min</div>
                                    <div class="small text-muted">Time Taken</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-secondary bg-opacity-10 rounded text-center">
                                    <div class="h4 fw-bold text-secondary">{{ $user->total_quizzes_taken }}</div>
                                    <div class="small text-muted">Total Quizzes</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Results --}}
                    <div class="mb-5">
                        <h2 class="h5 fw-semibold mb-3">Detailed Results</h2>
                        <div class="overflow-auto" style="max-height: 400px;">
                            @foreach($results['answers'] as $index => $answer)
                            <div class="card mb-3 {{ $answer['is_correct'] ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-2" style="font-family: 'Times New Roman', serif;">
                                            {{ $answer['greek_word'] }}
                                        </div>
                                        <div class="small">
                                            <div class="mb-1">
                                                <span class="text-muted">Your answer:</span>
                                                <span class="{{ $answer['is_correct'] ? 'text-success' : 'text-danger' }}">
                                                    {{ $answer['user_answer'] }}
                                                </span>
                                            </div>
                                            @if(!$answer['is_correct'])
                                            <div>
                                                <span class="text-muted">Correct answer:</span>
                                                <span class="text-success fw-medium">{{ $answer['correct_answer'] }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3 fs-4">
                                        @if($answer['is_correct'])
                                            <span class="text-success">✓</span>
                                        @else
                                            <span class="text-danger">✗</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('quiz.index') }}" class="btn btn-primary btn-lg">
                            Take Another Quiz
                        </a>
                        <a href="{{ route('quiz.leaderboard') }}" class="btn btn-success btn-lg">
                            View Leaderboard
                        </a>
                        <a href="{{ route('words.index') }}" class="btn btn-secondary btn-lg">
                            Browse Words
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
