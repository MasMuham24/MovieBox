@extends('layouts.app')

@section('title', 'My Watchlist – MovieBox')

@section('content')
    <section class="page">
        <div class="page-header">
            <h1 class="page-title">My Watchlist</h1>
        </div>

        @if (count($movies) === 0)
            <div class="empty-state">
                <p class="empty-title">Your watchlist is empty.</p>
                <p class="empty-text">Add movies you plan to watch and they will appear here.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Browse Movies</a>
            </div>
        @else
            <p class="search-count">{{ count($movies) }} saved movie{{ count($movies) > 1 ? 's' : '' }}</p>
            <div class="grid">
                @foreach ($movies as $movie)
                    @include('partials.movie-card', [
                        'movie' => $movie,
                        'favoriteIds' => [],
                        'watchlistIds' => $watchlistIds ?? [],
                    ])
                @endforeach
            </div>
        @endif
    </section>
@endsection