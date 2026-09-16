<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LayarKaca21Service
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('layarkaca21.base_url');
    }

    protected function request(string $endpoint, array $query = []): array
    {
        try {
            $response = Http::timeout(10)
                ->connectTimeout(5)
                ->get($this->baseUrl . $endpoint, $query);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            return [
                'error' => true,
                'status' => $response->status(),
                'results' => [],
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Failed to fetch data',
                'results' => [],
            ];
        }
    }

    public function latest(int $page = 1): array
    {
        return $this->request('/latest', ['page' => $page]);
    }

    public function genre(string $genre, int $page = 1): array
    {
        return $this->request('/genre/' . urlencode($genre), ['page' => $page]);
    }

    public function year(int $year, int $page = 1): array
    {
        return $this->request('/year/' . $year, ['page' => $page]);
    }

    public function trending(int $page = 1): array
    {
        return $this->request('/trending', ['page' => $page]);
    }

    public function search(string $query, int $page = 1): array
    {
        return $this->request('/search', ['q' => $query, 'page' => $page]);
    }

    public function resolution(int $resolution, int $page = 1): array
    {
        return $this->request('/resolution/' . $resolution, ['page' => $page]);
    }

    public function release(int $page = 1): array
    {
        return $this->request('/release', ['page' => $page]);
    }

    public function popular(int $page = 1): array
    {
        return $this->request('/popular', ['page' => $page]);
    }

    public function imdbRating(int $page = 1): array
    {
        return $this->request('/imdb-rating', ['page' => $page]);
    }

    public function hdQuality(int $page = 1): array
    {
        return $this->request('/hd-quality', ['page' => $page]);
    }

    public function country(string $country, int $page = 1): array
    {
        return $this->request('/country/' . urlencode($country), ['page' => $page]);
    }

    public function alphabet(string $letter, int $page = 1): array
    {
        return $this->request('/alphabet/' . urlencode($letter), ['page' => $page]);
    }

    public function lists(string $type): array
    {
        return $this->request('/lists/' . urlencode($type));
    }
}
