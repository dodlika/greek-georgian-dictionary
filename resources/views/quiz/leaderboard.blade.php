{{-- resources/views/quiz/leaderboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-2">Quiz Leaderboard</h1>
                        <p class="text-muted">Top performers in Greek vocabulary quizzes</p>
                    </div>

                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
                                  <path d="M6 8V1h1v6.117L9.447 5.276l.553.832-3 2-3-2 .553-.832L6 7.117V1h1v7z"/>
                                  <path d="M8 15V8H7v7h1zm4.5 1h-11A1.5 1.5 0 0 1 0 14.5V3a1.5 1.5 0 0 1 1.5-1.5H3v1H1.5a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-1h1v1a1.5 1.5 0 0 1-1.5 1.5zm.5-3V2a2 2 0 0 0-2-2H5v1h6a1 1 0 0 1 1 1v11h1z"/>
                                </svg>
                            </div>
                            <p class="text-muted">No quiz scores yet. Be the first to take a quiz!</p>
                            <div class="mt-3">
                                <a href="{{ route('quiz.index') }}" class="btn btn-primary">
                                    Take Quiz
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="vstack gap-3">
                            @foreach($users as $index => $leaderUser)
                                @php
                                    $bgClass = match($index) {
                                        0 => 'bg-warning bg-opacity-10 border border-warning',
                                        1 => 'bg-light border border-secondary',
                                        2 => 'bg-orange bg-opacity-10 border border-warning',
                                        default => 'bg-white border border-light'
                                    };
                                    $badgeClass = match($index) {
                                        0 => 'bg-warning text-white',
                                        1 => 'bg-secondary text-white',
                                        2 => 'bg-orange text-white',
                                        default => 'bg-primary bg-opacity-10 text-primary'
                                    };
                                    $scoreTextClass = match($index) {
                                        0 => 'text-warning',
                                        1 => 'text-secondary',
                                        2 => 'text-orange',
                                        default => 'text-primary'
                                    };
                                @endphp
                                <div class="d-flex align-items-center p-3 rounded {{ $bgClass }}">
                                    {{-- Rank --}}
                                    <div class="flex-shrink-0 me-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center {{ $badgeClass }}" style="width: 48px; height: 48px;">
                                            <span class="fw-bold">
                                                @if($index == 0) 🥇
                                                @elseif($index == 1) 🥈
                                                @elseif($index == 2) 🥉
                                                @else {{ $index + 1 }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- User Info --}}
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 {{ $leaderUser->id == Auth::id() ? 'text-primary' : '' }}">
                                            {{ $leaderUser->name }}
                                            @if($leaderUser->id == Auth::id())
                                                <small class="text-muted">(You)</small>
                                            @endif
                                        </h5>
                                        <div class="small text-muted">
                                            {{ $leaderUser->total_quizzes_taken }} quizzes • 
                                            Best: {{ $leaderUser->best_quiz_score }}/{{ $leaderUser->best_quiz_total }} • 
                                            {{ $leaderUser->best_quiz_date->format('M j, Y') }}
                                        </div>
                                    </div>

                                    {{-- Score --}}
                                    <div class="text-end">
                                        <span class="fs-4 fw-bold {{ $scoreTextClass }}">
                                            {{ $leaderUser->best_percentage }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-center gap-3 mt-5 flex-wrap">
                        <a href="{{ route('quiz.index') }}" class="btn btn-primary px-4 py-2">
                            Take Quiz
                        </a>
                        <a href="{{ route('words.index') }}" class="btn btn-secondary px-4 py-2">
                            Browse Words
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional: Custom Orange color --}}
<style>
    .text-orange { color: #fd7e14 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
</style>
@endsection
