@extends('layouts.app')

@section('title', ($movie['title'] ?? 'Movie') . ' – MovieBox')

@section('content')
    @php
        $backdrop = ! empty($movie['backdrop_path']) ? 'https://image.tmdb.org/t/p/original' . $movie['backdrop_path'] : null;
        $poster = ! empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : null;
        $year = isset($movie['release_date']) && $movie['release_date'] ? substr($movie['release_date'], 0, 4) : null;
        $rating = isset($movie['vote_average']) ? number_format($movie['vote_average'], 1) : null;
        $votes = $movie['vote_count'] ?? null;
        $runtime = (int) ($movie['runtime'] ?? 0);
        $runtimeText = $runtime > 0
            ? (intdiv($runtime, 60) . 'h ' . ($runtime % 60) . 'm')
            : null;
        $isFav = in_array($movie['id'], $favoriteIds ?? [], true);
        $isWatch = in_array($movie['id'], $watchlistIds ?? [], true);
    @endphp

    <section class="detail">
        @if ($backdrop)
            <img class="detail-backdrop" src="{{ $backdrop }}" alt="">
        @endif
        <div class="detail-shade"></div>

        <div class="detail-content">
            <div class="detail-inner">
                @if ($poster)
                    <div class="detail-poster">
                        <img src="{{ $poster }}" alt="{{ $movie['title'] ?? '' }}" onerror="this.style.display='none'">
                    </div>
                @endif

                <div class="detail-info">
                    <h1 class="detail-title">{{ $movie['title'] ?? 'Untitled' }}</h1>

                    <div class="detail-meta">
                        @if ($rating)
                            <span class="detail-rating">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M12 2l3 6.3 6.9 1-5 4.8 1.2 6.9L12 17.8 5.9 21l1.2-6.9-5-4.8 6.9-1z"/>
                                </svg>
                                {{ $rating }} / 10
                            </span>
                        @endif
                        @if ($votes)
                            <span class="detail-meta-item">{{ number_format($votes) }} votes</span>
                        @endif
                        @if ($year)
                            <span class="detail-meta-item">{{ $year }}</span>
                        @endif
                        @if ($runtimeText)
                            <span class="detail-meta-item">{{ $runtimeText }}</span>
                        @endif
                    </div>

                    @if (! empty($movie['genres']))
                        <div class="detail-genres">
                            @foreach ($movie['genres'] as $genre)
                                <span class="detail-genre">{{ $genre['name'] }}</span>
                            @endforeach
                        </div>
                    @endif

                    <p class="detail-overview">{{ $movie['overview'] ?? 'No overview available.' }}</p>

                    @if (! empty($movie['tagline']))
                        <p class="detail-tagline">"{{ $movie['tagline'] }}"</p>
                    @endif

                    <div class="detail-actions">
                        <button type="button" class="btn btn-primary btn-lg btn-watch" data-toast="Streaming not available yet.">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M6 4l14 8-14 8z"/>
                            </svg>
                            Watch Now
                        </button>

                        <form action="{{ route('favorites.toggle', $movie['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-lg {{ $isFav ? 'is-in-list' : '' }}" data-action="favorite" data-active="{{ $isFav ? '1' : '0' }}">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s-7.5-4.7-9.7-9A5.6 5.6 0 0 1 12 6.4 5.6 5.6 0 0 1 21.7 12c-2.2 4.3-9.7 9-9.7 9z"/>
                                </svg>
                                <span data-label>{{ $isFav ? 'Favorite' : 'Add to Favorites' }}</span>
                            </button>
                        </form>

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
        </div>
    </section>

    @php
        $trailer = null;
        foreach ($videos ?? [] as $video) {
            if (($video['site'] ?? '') === 'YouTube' && ($video['type'] ?? '') === 'Trailer') {
                $trailer = $video;
                break;
            }
        }
        if (!$trailer) {
            foreach ($videos ?? [] as $video) {
                if (($video['site'] ?? '') === 'YouTube' && in_array($video['type'] ?? '', ['Teaser', 'Clip'])) {
                    $trailer = $video;
                    break;
                }
            }
        }

        $indonesiaProviders = $watchProviders['ID'] ?? null;
        $flatrate = $indonesiaProviders['flatrate'] ?? [];
        $rent = $indonesiaProviders['rent'] ?? [];
        $buy = $indonesiaProviders['buy'] ?? [];
        $tmdbLink = $indonesiaProviders['link'] ?? null;
    @endphp

    @if ($trailer)
        <section class="detail-section">
            <div class="detail-section-inner">
                <h2 class="detail-section-title">Trailer</h2>
                <div class="detail-trailer">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $trailer['key'] ?? '' }}?rel=0&modestbranding=1"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="detail-trailer-iframe"
                        title="{{ $trailer['name'] ?? 'Movie Trailer' }}"
                    ></iframe>
                </div>
            </div>
        </section>
    @endif

    @if ($flatrate || $rent || $buy)
        <section class="detail-section">
            <div class="detail-section-inner">
                <h2 class="detail-section-title">Where to Watch</h2>
                
                @if ($flatrate)
                    <div class="provider-category">
                        <h3 class="provider-category-title">Streaming</h3>
                        <div class="provider-grid">
                            @foreach ($flatrate as $provider)
                                <div class="provider-card">
                                    @if (!empty($provider['logo_path']))
                                        <img src="https://image.tmdb.org/t/p/original{{ $provider['logo_path'] }}"
                                             alt="{{ $provider['provider_name'] ?? '' }}"
                                             class="provider-logo"
                                        >
                                    @endif
                                    <span class="provider-name">{{ $provider['provider_name'] ?? 'Unknown' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($rent)
                    <div class="provider-category">
                        <h3 class="provider-category-title">Rent</h3>
                        <div class="provider-grid">
                            @foreach ($rent as $provider)
                                <div class="provider-card">
                                    @if (!empty($provider['logo_path']))
                                        <img src="https://image.tmdb.org/t/p/original{{ $provider['logo_path'] }}"
                                             alt="{{ $provider['provider_name'] ?? '' }}"
                                             class="provider-logo"
                                        >
                                    @endif
                                    <span class="provider-name">{{ $provider['provider_name'] ?? 'Unknown' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($buy)
                    <div class="provider-category">
                        <h3 class="provider-category-title">Buy</h3>
                        <div class="provider-grid">
                            @foreach ($buy as $provider)
                                <div class="provider-card">
                                    @if (!empty($provider['logo_path']))
                                        <img src="https://image.tmdb.org/t/p/original{{ $provider['logo_path'] }}"
                                             alt="{{ $provider['provider_name'] ?? '' }}"
                                             class="provider-logo"
                                        >
                                    @endif
                                    <span class="provider-name">{{ $provider['provider_name'] ?? 'Unknown' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="provider-attribution">
                    <p class="provider-attribution-text">
                        Data provided by 
                        @if ($tmdbLink)
                            <a href="{{ $tmdbLink }}" target="_blank" rel="noopener" class="provider-link">JustWatch</a>
                        @else
                            JustWatch
                        @endif
                        via 
                        <a href="https://www.themoviedb.org/movie/{{ $movie['id'] ?? '' }}" target="_blank" rel="noopener" class="provider-link">TMDB</a>
                    </p>
                </div>
            </div>
        </section>
    @elseif(isset($watchProviders) && !$indonesiaProviders)
        <section class="detail-section">
            <div class="detail-section-inner">
                <h2 class="detail-section-title">Where to Watch</h2>
                <p class="provider-unavailable">Currently unavailable in Indonesia.</p>
                <div class="provider-attribution">
                    <p class="provider-attribution-text">
                        Data provided by 
                        <a href="https://www.justwatch.com/" target="_blank" rel="noopener" class="provider-link">JustWatch</a>
                        via 
                        <a href="https://www.themoviedb.org/movie/{{ $movie['id'] ?? '' }}" target="_blank" rel="noopener" class="provider-link">TMDB</a>
                    </p>
                </div>
            </div>
        </section>
    @endif

    <div class="rows">
        @include('partials.movie-row', [
            'title' => 'More Like This',
            'movies' => $similar ?? [],
            'favoriteIds' => $favoriteIds ?? [],
            'watchlistIds' => $watchlistIds ?? [],
        ])
    </div>
@endsection