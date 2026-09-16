@php
    $movieId = $movie['id'] ?? null;
    $poster = ! empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : null;
    $backdrop = ! empty($movie['backdrop_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path'] : null;
    $year = isset($movie['release_date']) && $movie['release_date'] ? substr($movie['release_date'], 0, 4) : null;
    $rating = isset($movie['vote_average']) ? number_format($movie['vote_average'], 1) : null;
    $isFav = $movieId && in_array($movieId, $favoriteIds ?? [], true);
    $isWatch = $movieId && in_array($movieId, $watchlistIds ?? [], true);
    $img = $poster ?? $backdrop;
@endphp

<article class="card" data-movie-id="{{ $movieId }}">
    <a href="{{ route('movie.show', $movieId) }}" class="card-link" aria-label="{{ $movie['title'] ?? 'Movie' }}"></a>

    <div class="card-poster">
        @if ($img)
            <img src="{{ $img }}" alt="{{ $movie['title'] ?? 'Movie poster' }}" loading="lazy"
                 onerror="this.parentElement.classList.add('is-broken')">
        @else
            <div class="card-placeholder">{{ strtoupper(substr($movie['title'] ?? 'M', 0, 2)) }}</div>
        @endif

        <div class="card-poster-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                <path d="M6 4l14 8-14 8z"/>
            </svg>
        </div>

        <div class="card-overlay">
            @if ($movieId)
                <div class="card-actions">
                    <form action="{{ route('favorites.toggle', $movieId) }}" method="POST">
                        @csrf
                        <button type="submit" class="card-action-btn {{ $isFav ? 'is-active' : '' }}" data-action="favorite"
                                data-active="{{ $isFav ? '1' : '0' }}" aria-label="{{ $isFav ? 'Remove from favorites' : 'Add to favorites' }}">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path d="M12 21s-7.5-4.7-9.7-9A5.6 5.6 0 0 1 12 6.4 5.6 5.6 0 0 1 21.7 12c-2.2 4.3-9.7 9-9.7 9z"/>
                            </svg>
                        </button>
                    </form>

                    <form action="{{ route('watchlist.toggle', $movieId) }}" method="POST">
                        @csrf
                        <button type="submit" class="card-action-btn {{ $isWatch ? 'is-active' : '' }}" data-action="watchlist"
                                data-active="{{ $isWatch ? '1' : '0' }}" aria-label="{{ $isWatch ? 'Remove from watchlist' : 'Add to watchlist' }}">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="{{ $isWatch ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path d="M19 3H5v18l7-4 7 4z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <a href="{{ route('movie.show', $movieId) }}" class="btn btn-small btn-outline btn-details">View Details</a>
            @endif
        </div>
    </div>

    <div class="card-info">
        <h3 class="card-title">{{ $movie['title'] ?? 'Untitled' }}</h3>
        <div class="card-meta">
            @if ($rating)
                <span class="card-rating">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor">
                        <path d="M12 2l3 6.3 6.9 1-5 4.8 1.2 6.9L12 17.8 5.9 21l1.2-6.9-5-4.8 6.9-1z"/>
                    </svg>
                    {{ $rating }}
                </span>
            @endif
            @if ($year)
                <span class="card-year">{{ $year }}</span>
            @endif
        </div>
    </div>
</article>