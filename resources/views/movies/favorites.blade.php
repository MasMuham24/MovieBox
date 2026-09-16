@extends('layouts.app')

@section('title', 'My Favorites – MovieBox')

@section('content')
    <section class="page">
        <div class="page-header">
            <h1 class="page-title">My Favorites</h1>
        </div>

        @if (count($movies) === 0)
            <div class="empty-state">
                <p class="empty-title">No movies added to your favorites yet.</p>
                <p class="empty-text">Tap the heart on any movie to save it here.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Explore Movies</a>
            </div>
        @else
            <p class="search-count">{{ count($movies) }} saved movie{{ count($movies) > 1 ? 's' : '' }}</p>
            <div class="grid">
                @foreach ($movies as $movie)
                    @include('partials.movie-card', [
                        'movie' => $movie,
                        'favoriteIds' => $favoriteIds ?? [],
                        'watchlistIds' => [],
                    ])
                @endforeach
            </div>
        @endif
    </section>
@endsection