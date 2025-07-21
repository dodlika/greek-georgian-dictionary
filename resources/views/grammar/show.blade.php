@extends('layouts.app')

@section('title', $sectionTitle . ' - Greek Grammar')

@section('content')
<div class="container py-5">
    <div class="mx-auto" style="max-width: 960px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('grammar.index') }}">Grammar</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $sectionTitle }}</li>
            </ol>
             <a href="{{ route('words.index') }}" class="btn btn-primary">
        ← Back to Homepage
    </a>
        </nav>

        <!-- Header -->
        <div class="mb-4">
            <h1 class="display-5 fw-bold">{{ $sectionTitle }}</h1>
            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach($sections as $key => $title)
                    <a href="{{ route('grammar.show', $key) }}"
                       class="btn btn-sm
                              {{ $key === $section ? 'btn-primary text-white' : 'btn-outline-secondary' }}">
                        {{ $title }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded shadow p-4 mb-4">
            @switch($section)
                @case('alphabet')
                    @include('grammar.sections.alphabet', ['data' => $data])
                    @break
                @case('articles')
                    @include('grammar.sections.articles', ['data' => $data])
                    @break
                @case('nouns')
                    @include('grammar.sections.nouns', ['data' => $data])
                    @break
                @case('adjectives')
                    @include('grammar.sections.adjectives', ['data' => $data])
                    @break
                @case('pronouns')
                    @include('grammar.sections.pronouns', ['data' => $data])
                    @break
                @case('verbs')
                    @include('grammar.sections.verbs', ['data' => $data])
                    @break
                @case('tenses')
                    @include('grammar.sections.tenses', ['data' => $data])
                    @break
                @case('prepositions')
                    @include('grammar.sections.prepositions', ['data' => $data])
                    @break
                @case('numbers')
                    @include('grammar.sections.numbers', ['data' => $data])
                    @break
                @case('syntax')
                    @include('grammar.sections.syntax', ['data' => $data])
                    @break
                @case('expressions')
                    @include('grammar.sections.expressions', ['data' => $data])
                    @break
            @endswitch
        </div>

        <!-- Navigation -->
        @php
            $sectionKeys = array_keys($sections);
            $currentIndex = array_search($section, $sectionKeys);
            $prevSection = $currentIndex > 0 ? $sectionKeys[$currentIndex - 1] : null;
            $nextSection = $currentIndex < count($sectionKeys) - 1 ? $sectionKeys[$currentIndex + 1] : null;
        @endphp

        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if($prevSection)
                    <a href="{{ route('grammar.show', $prevSection) }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i> {{ $sections[$prevSection] }}
                    </a>
                @endif
            </div>

            <a href="{{ route('grammar.index') }}" class="btn btn-primary">
                Back to Overview
            </a>

            <div>
                @if($nextSection)
                    <a href="{{ route('grammar.show', $nextSection) }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                        {{ $sections[$nextSection] }} <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
