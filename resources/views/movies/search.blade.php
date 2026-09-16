@extends('layouts.app')

@section('title', 'Search – MovieBox')

@section('content')
    <section class="page search-page">
        <div class="page-header">
            <h1 class="page-title">Search</h1>
        </div>

        <form action="{{ route('search') }}" method="GET" class="search-bar" id="searchBarForm" role="search">
            <div class="search-bar-inner">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                </svg>
                <input type="text" name="q" id="searchInput" class="search-input"
                       placeholder="Search movies..." value="{{ $query }}" autofocus autocomplete="off">
                @if ($query)
                    <a href="{{ route('search') }}" class="search-clear" aria-label="Clear search">×</a>
                @endif
            </div>
        </form>

        <div class="search-status" id="searchStatus" aria-live="polite"></div>

        @if ($query === '')
            <div class="empty-state">
                <p class="empty-title">What are you looking for?</p>
                <p class="empty-text">Search by movie title to start exploring.</p>
            </div>
        @elseif (count($movies) === 0)
            <div class="empty-state">
                <p class="empty-title">No movies found for "{{ $query }}"</p>
                <p class="empty-text">Try a different title.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Explore Movies</a>
            </div>
        @else
            <p class="search-count">{{ count($movies) }} result{{ count($movies) > 1 ? 's' : '' }} for "{{ $query }}"</p>
            <div class="grid">
                @foreach ($movies as $movie)
                    @include('partials.movie-card', [
                        'movie' => $movie,
                        'favoriteIds' => $favoriteIds ?? [],
                        'watchlistIds' => $watchlistIds ?? [],
                    ])
                @endforeach
            </div>
        @endif
    </section>
@endsection