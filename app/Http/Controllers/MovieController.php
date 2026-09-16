<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;

class MovieController extends Controller
{
    public function testTmdb(TmdbService $tmdb)
    {
        $movies = $tmdb->getPopularMovies();
        return response()->json($movies);
    }
}
