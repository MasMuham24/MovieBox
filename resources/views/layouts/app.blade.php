<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MovieBox')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="mb-body">
    @php
        $currentRoute = request()->routeIs('dashboard') ? 'dashboard'
            : (request()->routeIs('favorites') ? 'favorites'
            : (request()->routeIs('watchlist') ? 'watchlist'
            : (request()->routeIs('search') ? 'search' : '')));
        $nav = [
            ['label' => 'Home', 'url' => route('dashboard'), 'active' => $currentRoute === 'dashboard'],
            ['label' => 'Movies', 'url' => route('dashboard') . '#now-playing', 'active' => false],
            ['label' => 'Popular', 'url' => route('dashboard') . '#popular', 'active' => false],
            ['label' => 'Top Rated', 'url' => route('dashboard') . '#top-rated', 'active' => false],
            ['label' => 'Favorites', 'url' => route('favorites'), 'active' => $currentRoute === 'favorites'],
            ['label' => 'Watchlist', 'url' => route('watchlist'), 'active' => $currentRoute === 'watchlist'],
        ];
        $isGuest = ! auth()->check();
    @endphp

    <header class="mb-header" id="siteHeader">
        <div class="mb-header-inner">
            <a href="{{ route('dashboard') }}" class="mb-logo" aria-label="MovieBox home">
                <span class="mb-logo-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                        <path d="M6 4l14 8-14 8z"/>
                    </svg>
                </span>
                <span class="mb-logo-word">MovieBox</span>
            </a>

            @if (! $isGuest)
                <nav class="mb-nav" aria-label="Main navigation">
                    @foreach ($nav as $item)
                        <a href="{{ $item['url'] }}"
                           class="mb-nav-link {{ $item['active'] ? 'is-active' : '' }}"
                        >{{ $item['label'] }}</a>
                    @endforeach
                </nav>

                <div class="mb-header-actions">
                    <button type="button" class="mb-icon-btn mb-search-toggle" id="searchToggle" aria-label="Search">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                        </svg>
                    </button>

                    <div class="mb-profile" id="profileMenu">
                        <button type="button" class="mb-profile-trigger" id="profileTrigger" aria-label="Account menu" aria-expanded="false">
                            <span class="mb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <svg class="mb-chevron" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="mb-profile-dropdown" id="profileDropdown">
                            <div class="mb-profile-info">
                                <span class="mb-profile-name">{{ auth()->user()->name }}</span>
                                <span class="mb-profile-email">{{ auth()->user()->email }}</span>
                            </div>
                            <hr class="mb-divider">
                            <a class="mb-dropdown-item" href="{{ route('favorites') }}">My Favorites</a>
                            <a class="mb-dropdown-item" href="{{ route('watchlist') }}">My Watchlist</a>
                            <hr class="mb-divider">
                            <form action="{{ route('logout') }}" method="POST" class="mb-logout-form">
                                @csrf
                                <button type="submit" class="mb-dropdown-item mb-dropdown-danger" id="logoutBtn">Sign out of MovieBox</button>
                            </form>
                        </div>
                    </div>
                </div>

                <button type="button" class="mb-icon-btn mb-burger" id="burgerBtn" aria-label="Open menu">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
            @endif
        </div>

        <form action="{{ route('search') }}" method="GET" class="mb-header-search" id="headerSearch" role="search">
            <div class="mb-header-search-inner">
                <button type="submit" class="mb-search-submit" aria-label="Submit search">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                    </svg>
                </button>
                <input type="text" name="q" class="mb-header-search-input" id="headerSearchInput"
                       placeholder="Titles, people, genres" autocomplete="off"
                       value="{{ request('q') }}">
                <button type="button" class="mb-search-close" id="searchClose" aria-label="Close search">×</button>
            </div>
        </form>
    </header>

    @if (! $isGuest)
        <div class="mb-drawer-backdrop" id="drawerBackdrop"></div>
        <aside class="mb-drawer" id="drawer" aria-label="Mobile navigation">
            <div class="mb-drawer-header">
                <a href="{{ route('dashboard') }}" class="mb-logo">
                    <span class="mb-logo-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                            <path d="M6 4l14 8-14 8z"/>
                        </svg>
                    </span>
                    <span class="mb-logo-word">MovieBox</span>
                </a>
                <button type="button" class="mb-icon-btn" id="drawerClose" aria-label="Close menu">×</button>
            </div>
            <nav class="mb-drawer-nav">
                @foreach ($nav as $item)
                    <a href="{{ $item['url'] }}" class="mb-drawer-link {{ $item['active'] ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <div class="mb-drawer-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-block">Logout</button>
                </form>
            </div>
        </aside>
    @endif

    <div class="mb-search-overlay" id="mobileSearchOverlay" aria-hidden="true">
        <div class="mb-search-overlay-inner">
            <form action="{{ route('search') }}" method="GET" role="search">
                <div class="mb-search-overlay-bar">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                    </svg>
                    <input type="text" name="q" class="mb-search-overlay-input" id="mobileSearchInput" placeholder="Titles, people, genres" autofocus>
                    <button type="button" class="mb-search-overlay-close" id="mobileSearchClose" aria-label="Close search">×</button>
                </div>
            </form>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    @if (session('success'))
        <div class="mb-toast" id="toast" role="status">{{ session('success') }}</div>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>