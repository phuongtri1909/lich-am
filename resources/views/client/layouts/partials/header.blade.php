<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    
    <meta name="author" content="Lịch Âm Việt Nam">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="google-site-verification" content="" />
    
    @stack('meta')

    {{-- Dynamic Meta Tags from Database --}}
    @if($headerMetaTags)
        {!! $headerMetaTags->getFormattedMetaTags() !!}
        @if($headerMetaTags->gtag_code)
            {!! $headerMetaTags->getFormattedGtagCode() !!}
        @endif
        @if($headerMetaTags->custom_scripts)
            {!! $headerMetaTags->getFormattedCustomScripts() !!}
        @endif
    @endif

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->

    {{-- styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite('resources/assets/frontend/css/styles.css')
    @vite('resources/assets/frontend/css/header.css')
    @vite('resources/assets/frontend/css/footer.css')

    @stack('styles')

    {{-- end styles --}}
</head>

<body>
    <header class="header-main" id="header">
        <div class="container-custom">
            <div class="header-custom">
                <!-- Logo -->
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo/logo-site.png') }}" alt="Logo" style="margin-right: 20px;" height="30px">
                </a>
                <div class="d-flex align-items-center">

                    <div class="header-nav d-none d-xl-flex align-items-center">


                        <!-- Time Display -->
                        <div class="time-display">
                            <div class="clock-icon">🕐</div>
                            <span id="currentTime">13:46:18</span>
                        </div>

                        <a href="{{ route('home') }}"
                            class="{{ Route::currentRouteNamed('home') ? 'active' : '' }}">LỊCH ÂM HÔM NAY</a>
                        <a href="{{ route('converter') }}"
                            class="nav-link {{ Route::currentRouteNamed('converter') ? 'active' : '' }}">ĐỔI NGÀY ÂM
                            DƯƠNG</a>

                        <!-- Lịch tháng dropdown -->
                        <div class="dropdown">
                            <a href="#"
                                class="dropdown-toggle {{ Route::currentRouteNamed('month') ? 'active' : '' }}"
                                data-bs-toggle="dropdown">
                                LỊCH THÁNG <span class="dropdown-indicator">▼</span>
                            </a>
                            <ul class="dropdown-menu">
                                @for ($i = 1; $i <= 12; $i++)
                                    <li><a class="dropdown-item"
                                            href="{{ route('calendar.month', ['year' => date('Y'), 'month' => $i]) }}">Lịch Tháng
                                            {{ $i }}</a></li>
                                @endfor
                            </ul>
                        </div>

                        <!-- Lịch năm dropdown -->
                        <div class="dropdown">
                            <a href="#"
                                class="dropdown-toggle {{ Route::currentRouteNamed('year') ? 'active' : '' }}"
                                data-bs-toggle="dropdown">
                                LỊCH NĂM <span class="dropdown-indicator">▼</span>
                            </a>
                            <ul class="dropdown-menu">
                                @for ($i = date('Y') - 2; $i <= date('Y') + 10; $i++)
                                    <li><a class="dropdown-item"
                                            href="{{ route('calendar.year', ['year' => $i]) }}">Lịch Năm
                                            {{ $i }}</a></li>
                                @endfor
                            </ul>
                        </div>


                    </div>
                </div>

                <div class="d-xl-none">
                    <button class="btn border rounded-circle bg-white" id="mobileMenuToggle">
                        <i class="fa-solid fa-bars text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>



    <!-- Mobile Side Menu Overlay -->
    <div class="mobile-side-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Mobile Side Menu -->
    <div class="mobile-side-menu" id="mobileSideMenu">
        <div class="mobile-menu-header">
            <img src="{{ asset('images/logo/logo-site.png') }}" alt="Logo" height="40px">
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Mobile Time Display -->
        <div class="mobile-time-display">
            <div class="time-display">
                <div class="clock-icon">🕐</div>
                <span id="mobileCurrentTime">13:46:18</span>
            </div>
        </div>

        <ul class="mobile-nav-list">
            
            <li><a href="{{ route('home') }}" class="{{ Route::currentRouteNamed('today') ? 'active' : '' }}">LỊCH ÂM HÔM NAY</a>
            </li>
            <li><a href="{{ route('converter') }}" class="{{ Route::currentRouteNamed('convert') ? 'active' : '' }}">ĐỔI NGÀY ÂM
                    DƯƠNG</a></li>

            <!-- Mobile Lịch tháng dropdown -->
            <li class="mobile-dropdown">
                <a href="{{ route('calendar.month', ['year' => date('Y'), 'month' => date('m')]) }}"
                    class="{{ Route::currentRouteNamed('month') ? 'active' : '' }} mobile-dropdown-toggle">
                    LỊCH THÁNG <span class="dropdown-indicator">▼</span>
                </a>
                <ul class="mobile-dropdown-menu">
                    @for ($i = 1; $i <= 12; $i++)
                        <li><a href="{{ route('calendar.month', ['year' => date('Y'), 'month' => $i]) }}">Tháng
                                {{ $i }}</a></li>
                    @endfor
                </ul>
            </li>

            <!-- Mobile Lịch năm dropdown -->
            <li class="mobile-dropdown">
                <a href="{{ route('calendar.year', ['year' => date('Y')]) }}"
                    class="{{ Route::currentRouteNamed('year') ? 'active' : '' }} mobile-dropdown-toggle">
                    LỊCH NĂM <span class="dropdown-indicator">▼</span>
                </a>
                <ul class="mobile-dropdown-menu">
                    @for ($i = date('Y') - 2; $i <= date('Y') + 10; $i++)
                        <li><a href="{{ route('calendar.year', ['year' => $i]) }}">Năm {{ $i }}</a></li>
                    @endfor
                </ul>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileSideMenu = document.getElementById('mobileSideMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileMenuClose = document.getElementById('mobileMenuClose');

            // Sticky header behavior
            let lastScrollTop = 0;
            let ticking = false;

            function updateHeader() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                lastScrollTop = scrollTop;
                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }

            // Throttled scroll event
            window.addEventListener('scroll', requestTick, {
                passive: true
            });

            // Open mobile menu
            mobileMenuToggle.addEventListener('click', function() {
                mobileSideMenu.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Close mobile menu
            function closeMobileMenu() {
                mobileSideMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            mobileMenuClose.addEventListener('click', closeMobileMenu);
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);

            // Close menu when clicking on navigation links (but not dropdown toggles)
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-list a:not(.mobile-dropdown-toggle)');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });

            // Mobile dropdown functionality
            const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
            mobileDropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.parentElement;
                    const menu = dropdown.querySelector('.mobile-dropdown-menu');

                    // Close other dropdowns
                    document.querySelectorAll('.mobile-dropdown').forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('active');
                        }
                    });

                    // Toggle current dropdown
                    dropdown.classList.toggle('active');
                });
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1200) {
                    closeMobileMenu();
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerHeight = header.offsetHeight;
                        const targetPosition = target.offsetTop - headerHeight - 20;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Real-time clock update
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('vi-VN', {
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                const currentTimeElement = document.getElementById('currentTime');
                const mobileCurrentTimeElement = document.getElementById('mobileCurrentTime');

                if (currentTimeElement) {
                    currentTimeElement.textContent = timeString;
                }
                if (mobileCurrentTimeElement) {
                    mobileCurrentTimeElement.textContent = timeString;
                }
            }

            // Update time immediately and then every second
            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
