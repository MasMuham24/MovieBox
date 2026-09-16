@extends('layouts.app')

@section('title', 'MovieBox – Home')

@section('content')
    @if (! empty($featured))
        <section class="hero" id="hero" aria-label="Featured movies">
            @foreach ($featured as $index => $movie)
                @php
                    $backdrop = ! empty($movie['backdrop_path']) ? 'https://image.tmdb.org/t/p/original' . $movie['backdrop_path'] : null;
                    $year = isset($movie['release_date']) && $movie['release_date'] ? substr($movie['release_date'], 0, 4) : null;
                    $rating = isset($movie['vote_average']) ? number_format($movie['vote_average'], 1) : null;
                    $isWatch = in_array($movie['id'], $watchlistIds ?? [], true);
                @endphp
                <div class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-hero-slide>
                    @if ($backdrop)
                        <img class="hero-backdrop" src="{{ $backdrop }}" alt="" fetchpriority="{{ $index === 0 ? 'high' : 'low' }}">
                    @endif
                    <div class="hero-shade"></div>
                    <div class="hero-content">
                        <h1 class="hero-title">{{ $movie['title'] ?? 'Untitled' }}</h1>
                        <div class="hero-meta">
                            @if ($rating)
                                <span class="hero-rating">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                        <path d="M12 2l3 6.3 6.9 1-5 4.8 1.2 6.9L12 17.8 5.9 21l1.2-6.9-5-4.8 6.9-1z"/>
                                    </svg>
                                    {{ $rating }} / 10
                                </span>
                            @endif
                            @if ($year)
                                <span class="hero-year">{{ $year }}</span>
                            @endif
                            @if (! empty($movie['original_language']))
                                <span class="hero-badge">{{ strtoupper($movie['original_language']) }}</span>
                            @endif
                        </div>
                        <p class="hero-overview">{{ \Illuminate\Support\Str::limit($movie['overview'] ?? '', 220) }}</p>
                        <div class="hero-actions">
                            <a href="{{ route('movie.show', $movie['id']) }}" class="btn btn-primary btn-lg">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M6 4l14 8-14 8z"/>
                                </svg>
                                Watch Now
                            </a>
                            <form action="{{ route('watchlist.toggle', $movie['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-lg {{ $isWatch ? 'is-in-list' : '' }}" data-action="watchlist" data-active="{{ $isWatch ? '1' : '0' }}">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="{{ $isWatch ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                        <path d="M19 3H5v18l7-4 7 4z"/>
                                    </svg>
                                    <span data-label>{{ $isWatch ? 'In Watchlist' : 'Add to Watchlist' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if (count($featured) > 1)
                <div class="hero-dots" role="tablist" aria-label="Featured movies">
                    @foreach ($featured as $index => $movie)
                        <button type="button" class="hero-dot {{ $index === 0 ? 'is-active' : '' }}" data-hero-index="{{ $index }}" aria-label="Show featured {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    <div class="rows">
        @include('partials.movie-row', [
            'title' => 'Popular Movies',
            'anchor' => 'popular',
            'movies' => $sections['popular'] ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])

        @include('partials.movie-row', [
            'title' => 'Now Playing',
            'anchor' => 'now-playing',
            'movies' => $sections['nowPlaying'] ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])

        @include('partials.movie-row', [
            'title' => 'Top Rated',
            'anchor' => 'top-rated',
            'movies' => $sections['topRated'] ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])

        @include('partials.movie-row', [
            'title' => 'Upcoming Movies',
            'anchor' => 'upcoming',
            'movies' => $sections['upcoming'] ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])

        @include('partials.movie-row', [
            'title' => 'Recommended For You',
            'anchor' => 'recommended',
            'movies' => $recommended ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])
    </div>
@endsection