<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('tmdb.base_url');
        $this->apiKey = config('tmdb.api_key');
    }

    protected function request(string $path, array $query = []): array
    {
        return Http::get($this->baseUrl . $path, array_merge([
            'api_key' => $this->apiKey,
            'language' => 'id-ID',
            'page' => 1,
        ], $query))->json();
    }

    public function getPopularMovies(int $page = 1): array
    {
        return $this->request('/movie/popular', ['page' => $page]);
    }

    public function getNowPlaying(int $page = 1): array
    {
        return $this->request('/movie/now_playing', ['page' => $page]);
    }

    public function getTopRated(int $page = 1): array
    {
        return $this->request('/movie/top_rated', ['page' => $page]);
    }

    public function getUpcoming(int $page = 1): array
    {
        return $this->request('/movie/upcoming', ['page' => $page]);
    }

    public function getMovieDetails(int $movieId): array
    {
        return $this->request('/movie/' . $movieId);
    }

    public function getSimilarMovies(int $movieId, int $page = 1): array
    {
        return $this->request('/movie/' . $movieId . '/similar', ['page' => $page]);
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        return $this->request('/search/movie', ['query' => $query, 'page' => $page]);
    }
}