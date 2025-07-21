@extends('layouts.app')

@section('title', 'Greek Grammar Guide')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Greek Grammar Guide</h1>
        <p class="lead text-muted">Complete reference for Modern Greek grammar</p>
    </div>
    <div class="mt-4 text-center">
    <a href="{{ route('words.index') }}" class="btn btn-primary">
        ← Back to Homepage
    </a>
</div>

    <div class="row g-4">
        @foreach($sections as $key => $title)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            @switch($key)
                                @case('alphabet')
                                    <i class="bi bi-fonts fs-4 text-primary"></i>
                                    @break
                                @case('articles')
                                    <i class="bi bi-journal-text fs-4 text-primary"></i>
                                    @break
                                @case('nouns')
                                    <i class="bi bi-archive fs-4 text-primary"></i>
                                    @break
                                @case('verbs')
                                    <i class="bi bi-lightning-fill fs-4 text-primary"></i>
                                    @break
                                @default
                                    <i class="bi bi-book fs-4 text-primary"></i>
                            @endswitch
                        </div>
                        <h5 class="card-title mb-0">{{ $title }}</h5>
                    </div>
                    <p class="card-text text-muted">
                        @switch($key)
                            @case('alphabet')
                                Learn the Greek alphabet, pronunciation, and accent marks
                                @break
                            @case('articles')
                                Definite and indefinite articles with declensions
                                @break
                            @case('nouns')
                                Noun cases, declensions, and gender patterns
                                @break
                            @case('adjectives')
                                Adjective agreement, comparison, and forms
                                @break
                            @case('pronouns')
                                Personal, possessive, and demonstrative pronouns
                                @break
                            @case('verbs')
                                Verb conjugation patterns and irregular verbs
                                @break
                            @case('tenses')
                                All verb tenses with examples and usage
                                @break
                            @case('prepositions')
                                Common prepositions and their cases
                                @break
                            @case('numbers')
                                Cardinal and ordinal numbers
                                @break
                            @case('syntax')
                                Word order, questions, and sentence structure
                                @break
                            @case('expressions')
                                Common phrases and everyday expressions
                                @break
                            @default
                                Essential Greek grammar concepts
                        @endswitch
                    </p>
                    <a href="{{ route('grammar.show', $key) }}" class="btn btn-outline-primary btn-sm">
                        Study this topic <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5 p-4 bg-light rounded shadow-sm">
        <h2 class="h4 mb-4">Study Tips</h2>
        <div class="row">
            <div class="col-md-6">
                <h6>For Beginners:</h6>
                <ul class="text-muted small ps-3">
                    <li>Start with the alphabet and pronunciation</li>
                    <li>Master articles and basic noun cases</li>
                    <li>Learn present tense verb conjugation</li>
                    <li>Practice with common expressions</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>For Advanced:</h6>
                <ul class="text-muted small ps-3">
                    <li>Focus on complex tenses and moods</li>
                    <li>Master all noun declensions</li>
                    <li>Study advanced syntax patterns</li>
                    <li>Practice with varied sentence structures</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
