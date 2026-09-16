<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Watchlist;
use App\Services\TmdbService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieListController extends Controller
{
    public function dashboard(TmdbService $tmdb)
    {
        $sectionMovies = [
            'popular' => $tmdb->getPopularMovies()['results'] ?? [],
            'nowPlaying' => $tmdb->getNowPlaying()['results'] ?? [],
            'topRated' => $tmdb->getTopRated()['results'] ?? [],
            'upcoming' => $tmdb->getUpcoming()['results'] ?? [],
        ];

        $featured = array_slice($sectionMovies['popular'], 0, 3);

        $recommended = collect()
            ->merge($sectionMovies['popular'])
            ->merge($sectionMovies['topRated'])
            ->merge($sectionMovies['upcoming'])
            ->unique('id')
            ->shuffle()
            ->take(20)
            ->values()
            ->all();

        [, $watchlistIds] = $this->savedIds();

        return view('dashboard', [
            'featured' => $featured,
            'sections' => $sectionMovies,
            'recommended' => $recommended,
            'favoriteIds' => $this->savedIds()[0],
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function show(TmdbService $tmdb, int $movieId)
    {
        $movie = $tmdb->getMovieDetails($movieId);
        $similar = $tmdb->getSimilarMovies($movieId)['results'] ?? [];
        $videos = $tmdb->getMovieVideos($movieId);
        $watchProviders = $tmdb->getMovieWatchProviders($movieId);

        [$favoriteIds, $watchlistIds] = $this->savedIds();

        return view('movies.detail', [
            'movie' => $movie,
            'similar' => $similar,
            'videos' => $videos,
            'watchProviders' => $watchProviders,
            'favoriteIds' => $favoriteIds,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function search(Request $request, TmdbService $tmdb)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return view('movies.search', ['query' => '', 'movies' => []]);
        }

        $movies = $tmdb->searchMovies($query)['results'] ?? [];

        [$favoriteIds, $watchlistIds] = $this->savedIds();

        return view('movies.search', [
            'query' => $query,
            'movies' => $movies,
            'favoriteIds' => $favoriteIds,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function toggleFavorite(int $movieId): RedirectResponse
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('tmdb_movie_id', $movieId)->first();
        if ($favorite) {
            $favorite->delete();

            return back()->with('success', 'Movie removed from favorites.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'tmdb_movie_id' => $movieId,
        ]);

        return back()->with('success', 'Movie added to favorites.');
    }

    public function toggleWatchlist(int $movieId): RedirectResponse
    {
        $watchlist = Watchlist::where('user_id', Auth::id())->where('tmdb_movie_id', $movieId)->first();
        if ($watchlist) {
            $watchlist->delete();

            return back()->with('success', 'Movie removed from watchlist.');
        }

        Watchlist::create([
            'user_id' => Auth::id(),
            'tmdb_movie_id' => $movieId,
        ]);

        return back()->with('success', 'Movie added to watchlist.');
    }

    public function favorites(TmdbService $tmdb)
    {
        /** @var User $user */
        $user = Auth::user();
        $favorites = $user->favorites()->latest()->get();
        $movies = $this->hydrateMovies($favorites, $tmdb);

        $favoriteIds = $movies->keys()->all();

        return view('movies.favorites', [
            'movies' => $movies,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function watchlist(TmdbService $tmdb)
    {
        /** @var User $user */
        $user = Auth::user();
        $watchlist = $user->watchlists()->latest()->get();
        $movies = $this->hydrateMovies($watchlist, $tmdb);

        $watchlistIds = $movies->keys()->all();

        return view('movies.watchlist', [
            'movies' => $movies,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    protected function hydrateMovies($items, TmdbService $tmdb): \Illuminate\Support\Collection
    {
        return $items
            ->map(function ($item) use ($tmdb) {
                return $tmdb->getMovieDetails($item->tmdb_movie_id);
            })
            ->filter(fn ($movie) => isset($movie['id']))
            ->keyBy('id');
    }

    protected function savedIds(): array
    {
        $user = Auth::user();
        $favoriteIds = $user->favorites()->pluck('tmdb_movie_id')->map(fn ($id) => (int) $id)->all();
        $watchlistIds = $user->watchlists()->pluck('tmdb_movie_id')->map(fn ($id) => (int) $id)->all();

        return [$favoriteIds, $watchlistIds];
    }
}