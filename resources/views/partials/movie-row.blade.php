@if (! empty($movies) && count($movies) > 0)
    <section class="row" id="{{ $anchor ?? '' }}">
        <div class="row-header">
            <h2 class="row-title">{{ $title }}</h2>
            <div class="row-controls">
                <button type="button" class="row-arrow" data-row-prev aria-label="Scroll {{ $title }} left">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m15 6-6 6 6 6"/></svg>
                </button>
                <button type="button" class="row-arrow" data-row-next aria-label="Scroll {{ $title }} right">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
                </button>
            </div>
        </div>

        <div class="row-track" data-row-track>
            @foreach ($movies as $movie)
                @include('partials.movie-card', [
                    'movie' => $movie,
                    'favoriteIds' => $favoriteIds ?? [],
                    'watchlistIds' => $watchlistIds ?? [],
                ])
            @endforeach
        </div>
        <div class="row-edge row-edge-left" data-row-edge-left>
            <button type="button" class="row-arrow" data-row-prev aria-label="Scroll {{ $title }} left">‹</button>
        </div>
        <div class="row-edge row-edge-right" data-row-edge-right>
            <button type="button" class="row-arrow" data-row-next aria-label="Scroll {{ $title }} right">›</button>
        </div>
        <div class="row-fade row-fade-left" aria-hidden="true"></div>
        <div class="row-fade row-fade-right" aria-hidden="true"></div>
    </section>
@endif