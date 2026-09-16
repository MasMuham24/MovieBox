# 🎬 MovieBox

> A modern, cinematic movie discovery and personal watchlist platform built with Laravel and the TMDB API.

MovieBox is a full-stack movie web application designed to provide a modern streaming-platform-inspired experience for discovering movies, exploring detailed information, and managing personal favorites and watchlists.

The project focuses on clean architecture, responsive UI, API integration, and a scalable foundation for future streaming functionality.

---

## 🚀 Overview

MovieBox combines the power of the **TMDB API** with a custom Laravel backend and a cinematic frontend experience.

Users can:

* Discover popular and trending movies
* Explore different movie categories
* Search for movies
* View detailed movie information
* Save movies to Favorites
* Add movies to a Watchlist
* Browse personalized movie collections
* Navigate through a responsive streaming-style interface

The application is being developed incrementally using a **phase-based development approach**, allowing each major feature to be implemented, tested, and validated independently.

---

## ✨ Features

### 🎬 Movie Discovery

* Popular Movies
* Now Playing
* Top Rated
* Upcoming Movies
* Recommended Movies
* Similar Movies
* Movie search
* Dynamic movie metadata powered by TMDB

### 🎞️ Movie Details

Each movie provides:

* Title
* Poster
* Backdrop
* Release date
* Rating
* Overview
* Genres
* Related movies
* Watch action
* Favorite action
* Watchlist action

### ❤️ Favorites

Users can:

* Add movies to Favorites
* Remove movies from Favorites
* View their personal Favorites collection
* Toggle favorite status without a full page reload

### 🔖 Watchlist

Users can:

* Add movies to their Watchlist
* Remove movies from their Watchlist
* View saved movies
* Manage their personal watchlist

### 🔎 Search

MovieBox provides a dedicated movie search experience powered by TMDB, allowing users to quickly discover movies without relying on hardcoded data.

### 🎨 Modern Streaming UI

The interface is designed around a dark cinematic visual system inspired by modern streaming platforms.

Key UI features include:

* Hero movie slider
* Horizontal movie carousels
* Movie card hover interactions
* Sticky navigation
* Search overlay
* Profile dropdown
* Mobile navigation drawer
* Toast notifications
* Responsive layouts
* Dark cinematic theme

---

## 🛠️ Tech Stack

### Backend

* PHP
* Laravel
* Laravel Blade
* Laravel HTTP Client
* MySQL

### Frontend

* HTML5
* CSS3
* JavaScript
* Responsive UI

### APIs

* TMDB API
* Internet Archive API

### Development Environment

* XAMPP
* Composer
* NPM
* Git / GitHub

---

## 🏗️ Architecture

MovieBox follows a service-oriented Laravel structure to keep external API communication separated from controllers and presentation logic.

```text
                         ┌──────────────────┐
                         │      MovieBox     │
                         └────────┬─────────┘
                                  │
                     ┌────────────┴────────────┐
                     │                         │
              Laravel Backend             Frontend
                     │                         │
          ┌──────────┴──────────┐       ┌──────┴──────┐
          │                     │       │             │
     Controllers            Services   Blade        JavaScript
          │                     │       │             │
          │              ┌──────┴──────┐│             │
          │              │             ││             │
          │          TmdbService   InternetArchiveService
          │              │             │
          └──────────────┴─────────────┘
                         │
                  External APIs
```

---

## 📁 Project Structure

```text
MovieBox/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       ├── MovieController.php
│   │       └── MovieListController.php
│   │
│   ├── Models/
│   │   ├── Favorite.php
│   │   ├── Watchlist.php
│   │   └── User.php
│   │
│   └── Services/
│       ├── TmdbService.php
│       └── InternetArchiveService.php
│
├── config/
│   └── internetarchive.php
│
├── resources/
│   ├── css/
│   │   └── app.css
│   │
│   ├── js/
│   │   └── app.js
│   │
│   └── views/
│       ├── layouts/
│       ├── partials/
│       ├── dashboard.blade.php
│       └── movies/
│           ├── detail.blade.php
│           ├── search.blade.php
│           ├── favorites.blade.php
│           └── watchlist.blade.php
│
├── routes/
│   └── web.php
│
├── database/
│   └── migrations/
│
├── .env.example
├── artisan
├── composer.json
└── package.json
```

---

## 🔐 Authentication & User Data

MovieBox includes an authentication system that protects user-specific features.

Protected functionality includes:

* Dashboard access
* Favorites
* Watchlist
* User-specific movie collections

Favorites and Watchlist records are associated with authenticated users, ensuring that each user's saved movies remain independent.

---

## 🔌 API Integration

### TMDB

TMDB is used as the primary source for movie metadata, including:

* Movie information
* Posters
* Backdrops
* Ratings
* Release dates
* Genres
* Recommendations
* Search results
* Movie videos

API credentials are stored through environment variables and are not committed to the repository.

Example:

```env
TMDB_API_KEY=your_tmdb_api_key
```

### Internet Archive

Internet Archive integration has been prepared as part of the streaming architecture.

The `InternetArchiveService` currently provides functionality for:

* Searching archive items
* Retrieving item metadata
* Retrieving available files
* Detecting playable video formats
* Reading available rights/license metadata

Streaming availability is handled separately from movie metadata to keep the architecture flexible for future video providers.

---

## 📈 Development Roadmap

MovieBox is being developed incrementally.

### Phase 1 — Movie Platform Foundation

* [x] Authentication
* [x] TMDB integration
* [x] Movie dashboard
* [x] Movie details
* [x] Search
* [x] Favorites
* [x] Watchlist
* [x] Responsive streaming-style UI

### Phase 2 — Streaming Infrastructure

* [x] Internet Archive service
* [x] Archive item search
* [x] Metadata retrieval
* [x] Video file detection
* [x] Playable video detection

### Phase 3 — Streaming Integration

* [ ] Streaming provider matching
* [ ] Movie-to-video matching
* [ ] Watch route
* [ ] Video player
* [ ] Streaming availability handling

### Phase 4 — Enhanced Viewing Experience

* [ ] Continue Watching
* [ ] Watch History
* [ ] Playback progress
* [ ] Resume playback
* [ ] Improved player controls

### Future Improvements

* Advanced movie filtering
* Genre-based discovery
* User ratings and reviews
* Personalized recommendations
* Performance optimization
* Caching strategy
* Advanced player features
* Production deployment

---

## ⚡ Local Development

### 1. Clone the repository

```bash
git clone <YOUR_REPOSITORY_URL>
cd MovieBox
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Configure database

Update `.env`:

```env
DB_DATABASE=moviebox
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate
```

### 5. Configure TMDB

Add your TMDB API key:

```env
TMDB_API_KEY=your_tmdb_api_key
```

### 6. Build frontend assets

```bash
npm run build
```

### 7. Clear Laravel cache

```bash
php artisan optimize:clear
```

### 8. Start development server

```bash
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

---

## 🧪 Testing

Before committing changes, the following commands can be used to validate the application:

```bash
php artisan optimize:clear
php artisan route:list
php artisan view:cache
```

PHP syntax can also be checked with:

```bash
php -l path/to/file.php
```

---

## 🔒 Security Notes

Sensitive credentials should never be committed to GitHub.

Use `.env` for:

* API keys
* Database credentials
* Application secrets
* Environment-specific configuration

The `.env` file should remain excluded through `.gitignore`.

---

## 🎯 Project Goals

MovieBox is designed as both a functional movie platform and a practical Laravel portfolio project demonstrating:

* Laravel MVC architecture
* Service-layer API integration
* REST API consumption
* Authentication
* Database relationships
* AJAX interactions
* Dynamic Blade components
* Responsive frontend development
* External API handling
* Error handling
* Incremental feature development

---

## 📌 Current Status

**Development Status: Active**

Current implementation includes a complete movie discovery experience with authentication, TMDB integration, Favorites, Watchlist, Search, Movie Details, and a prepared streaming service layer.

The streaming player and provider integration are being developed separately to keep the application modular and maintainable.

---

## 👨‍💻 Author

**Muhammad Syafi'i**

Built as a Laravel portfolio project focused on modern web application development, API integration, and cinematic user experience.

---

## 📄 License

This project is intended for educational and portfolio purposes.

Movie metadata and imagery are provided through third-party services and remain subject to their respective terms and licenses.
