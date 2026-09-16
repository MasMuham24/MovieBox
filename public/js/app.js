(function () {
    'use strict';

    var $ = function (sel, root) { return (root || document).querySelector(sel); };
    var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };

    /* ---------- Toast ---------- */

    function showToast(message) {
        var old = $('#toast');
        if (old) old.remove();

        var toast = document.createElement('div');
        toast.className = 'mb-toast';
        toast.setAttribute('role', 'status');
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('is-leaving');
            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 3200);
    }

    /* ---------- Header scroll ---------- */

    var header = $('#siteHeader');
    function onScroll() {
        if (!header) return;
        header.classList.toggle('is-scrolled', window.scrollY > 40);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ---------- Desktop search expand ---------- */

    var searchToggle = $('#searchToggle');
    var headerSearch = $('#headerSearch');
    var headerSearchInput = $('#headerSearchInput');
    var searchClose = $('#searchClose');

    function openHeaderSearch() {
        headerSearch.classList.add('is-open');
        if (headerSearchInput) headerSearchInput.focus();
    }

    function closeHeaderSearch() {
        headerSearch.classList.remove('is-open');
    }

    if (searchToggle) {
        searchToggle.addEventListener('click', function () {
            if (window.innerWidth <= 1024) {
                openMobileSearch();
            } else {
                headerSearch.classList.contains('is-open') ? closeHeaderSearch() : openHeaderSearch();
            }
        });
    }
    if (searchClose) searchClose.addEventListener('click', closeHeaderSearch);
    if (headerSearchInput) {
        headerSearchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeHeaderSearch();
        });
    }

    /* ---------- Mobile search overlay ---------- */

    var overlay = $('#mobileSearchOverlay');
    var overlayInput = $('#mobileSearchInput');
    var overlayClose = $('#mobileSearchClose');

    function openMobileSearch() {
        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
        if (overlayInput) setTimeout(function () { overlayInput.focus(); }, 80);
    }

    function closeMobileSearch() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
    }

    if (overlayClose) overlayClose.addEventListener('click', closeMobileSearch);
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeMobileSearch();
        });
    }

    /* ---------- Mobile drawer ---------- */

    var burger = $('#burgerBtn');
    var drawer = $('#drawer');
    var drawerClose = $('#drawerClose');
    var drawerBackdrop = $('#drawerBackdrop');

    function openDrawer() { drawer.classList.add('is-open'); drawerBackdrop.classList.add('is-open'); }
    function closeDrawer() { drawer.classList.remove('is-open'); drawerBackdrop.classList.remove('is-open'); }

    if (burger) burger.addEventListener('click', openDrawer);
    if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);

    /* ---------- Profile dropdown ---------- */

    var profile = $('#profileMenu');
    var profileTrigger = $('#profileTrigger');

    if (profileTrigger) {
        profileTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = profile.classList.toggle('is-open');
            profileTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }
    document.addEventListener('click', function (e) {
        if (profile && !profile.contains(e.target)) {
            profile.classList.remove('is-open');
            if (profileTrigger) profileTrigger.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeHeaderSearch();
            closeMobileSearch();
            closeDrawer();
            if (profile) profile.classList.remove('is-open');
        }
    });

    /* ---------- Hero slider ---------- */

    var slides = $$('[data-hero-slide]');
    var dots = $$('[data-hero-index]');
    var current = 0;
    var heroTimer = null;

    function showSlide(index) {
        if (!slides.length) return;
        current = (index + slides.length) % slides.length;
        slides.forEach(function (s, i) { s.classList.toggle('is-active', i === current); });
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
    }

    function startHero() {
        if (!slides.length || slides.length === 1) return;
        heroTimer = setInterval(function () { showSlide(current + 1); }, 6000);
    }

    function restartHero() {
        if (heroTimer) clearInterval(heroTimer);
        startHero();
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            showSlide(parseInt(dot.dataset.heroIndex, 10));
            restartHero();
        });
    });

    startHero();

    /* ---------- Row horizontal scroll ---------- */

    $$('[data-row-track]').forEach(function (track) {
        var row = track.closest('.row');
        var prevs = $$('[data-row-prev]', row);
        var nexts = $$('[data-row-next]', row);
        var timeout = null;

        function step() {
            var card = track.querySelector('.card');
            var visible = Math.max(1, Math.floor(track.clientWidth / (card ? card.offsetWidth + 8 : 160)));
            return (card ? card.offsetWidth + 8 : 160) * visible;
        }

        function prep() {
            var s = step();
            prevs.forEach(function (b) { b.style.opacity = track.scrollLeft <= 2 ? '0.35' : '1'; });
            nexts.forEach(function (b) {
                var max = track.scrollWidth - track.clientWidth - 2;
                b.style.opacity = track.scrollLeft >= max ? '0.35' : '1';
            });
        }

        prevs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                track.scrollBy({ left: -step(), behavior: 'smooth' });
            });
        });
        nexts.forEach(function (btn) {
            btn.addEventListener('click', function () {
                track.scrollBy({ left: step(), behavior: 'smooth' });
            });
        });

        track.addEventListener('scroll', function () {
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(prep, 80);
        });
        window.addEventListener('resize', prep);
        prep();
    });

    /* ---------- Search page: debounced auto submit ---------- */

    var searchForm = $('#searchBarForm');
    var searchInput = $('#searchInput');
    var searchStatus = $('#searchStatus');

    if (searchForm && searchInput) {
        var debounce;

        searchForm.addEventListener('submit', function () {
            searchForm.classList.add('is-loading');
            if (searchStatus) searchStatus.textContent = 'Searching...';
        });

        searchInput.addEventListener('input', function () {
            if (debounce) clearTimeout(debounce);
            debounce = setTimeout(function () {
                if (searchInput.value.trim() !== (searchInput.dataset.searched || '')) {
                    searchForm.submit();
                }
            }, 500);
        });

        searchInput.dataset.searched = searchInput.value;
    }

    /* ---------- Favorite / Watchlist AJAX toggle ---------- */

    var actionLabels = {
        favorite: { add: 'Add to Favorites', added: 'Favorite', msgOn: 'Added to favorites.', msgOff: 'Removed from favorites.' },
        watchlist: { add: 'Add to Watchlist', added: 'In Watchlist', msgOn: 'Added to watchlist.', msgOff: 'Removed from watchlist.' }
    };

    $$('form').forEach(function (form) {
        var btn = $('[data-action]', form);
        if (!btn) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var action = btn.dataset.action;
            var wasActive = btn.dataset.active === '1';
            var labels = actionLabels[action] || actionLabels.watchlist;
            var isCardButton = btn.classList.contains('card-action-btn');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams(new FormData(form))
            }).then(function (res) {
                if (!res.ok) throw new Error('Request failed');
                var nowActive = !wasActive;
                var svg = $('svg', btn);

                btn.dataset.active = nowActive ? '1' : '0';
                btn.classList.toggle('is-active', nowActive);

                if (svg) svg.setAttribute('fill', nowActive ? 'currentColor' : 'none');

                if (isCardButton) {
                    btn.setAttribute('aria-label', nowActive
                        ? (action === 'favorite' ? 'Remove from favorites' : 'Remove from watchlist')
                        : (action === 'favorite' ? 'Add to favorites' : 'Add to watchlist'));
                } else {
                    var label = $('[data-label]', btn);
                    if (label) label.textContent = nowActive ? labels.added : labels.add;
                    btn.classList.toggle('is-in-list', nowActive);
                }

                showToast(nowActive ? labels.msgOn : labels.msgOff);
            }).catch(function () {
                showToast('Something went wrong.');
                form.submit();
            });
        });
    });

    /* ---------- Generic toast trigger ---------- */

    $$('[data-toast]').forEach(function (el) {
        el.addEventListener('click', function () {
            showToast(el.dataset.toast);
        });
    });

    /* ---------- Hash anchor offset ---------- */

    document.querySelectorAll('#siteHeader').forEach(function () {
        var navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h'), 10) || 64;
        setTimeout(function () {
            if (window.location.hash) {
                var target = $(window.location.hash);
                if (target) {
                    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - navH - 10, behavior: 'smooth' });
                }
            }
        }, 120);
    });
})();