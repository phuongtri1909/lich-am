<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
        $currentLocale = app()->getLocale();
        $seoTitle = 'Home - Cosmopark';
        $seoDescription = 'Cosmopark';
        $seoKeywords = 'Cosmopark,park';
        $seoThumbnail = asset('assets/images/dev/Thumbnail.png');

        // Get SEO data from view variables
        if (isset($seoSetting) && $seoSetting) {
            $seoTitle =
                $seoSetting->getTranslation('title', $currentLocale) ?: $seoSetting->getTranslation('title', 'vi');
            $seoDescription =
                $seoSetting->getTranslation('description', $currentLocale) ?:
                $seoSetting->getTranslation('description', 'vi');
            $seoKeywords =
                $seoSetting->getTranslation('keywords', $currentLocale) ?:
                $seoSetting->getTranslation('keywords', 'vi');
            $seoThumbnail = $seoSetting->thumbnail_url;
        } elseif (isset($seoData) && $seoData) {
            // For dynamic SEO (blog posts, projects)
            $seoTitle = $seoData->title;
            $seoDescription = $seoData->description;
            $seoKeywords = $seoData->keywords;
            $seoThumbnail = $seoData->thumbnail;
        }

        // Override with yield if provided (will be handled after PHP block)

    @endphp

    <title>
        @if ($seoTitle)
            {{ $seoTitle }}
        @elseif(@hasSection('title'))
            @yield('title')
        @else
            Home - Cosmopark
        @endif
    </title>
    <meta name="description"
        content="@if ($seoDescription) {{ $seoDescription }}@elseif(@hasSection('description'))@yield('description')@else Cosmopark @endif">
    <meta name="keywords"
        content="@if ($seoKeywords) {{ $seoKeywords }}@elseif(@hasSection('keywords'))@yield('keywords')@else Cosmopark,park @endif">
    <meta name="author" content="Cosmopark">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:title"
        content="@if ($seoTitle) {{ $seoTitle }}@elseif(@hasSection('title'))@yield('title')@else Home - Cosmopark @endif">
    <meta property="og:description"
        content="@if ($seoDescription) {{ $seoDescription }}@elseif(@hasSection('description'))@yield('description')@else Cosmopark @endif">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:image" content="{{ $seoThumbnail }}">
    <meta property="og:image:secure_url" content="{{ $seoThumbnail }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"
        content="@if ($seoTitle) {{ $seoTitle }}@elseif(@hasSection('title'))@yield('title')@else Home - Cosmopark @endif">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="@if ($seoTitle) {{ $seoTitle }}@elseif(@hasSection('title'))@yield('title')@else Home - Cosmopark @endif">
    <meta name="twitter:description"
        content="@if ($seoDescription) {{ $seoDescription }}@elseif(@hasSection('description'))@yield('description')@else Cosmopark @endif">
    <meta name="twitter:image" content="{{ $seoThumbnail }}">
    <meta name="twitter:image:alt"
        content="@if ($seoTitle) {{ $seoTitle }}@elseif(@hasSection('title'))@yield('title')@else Home - Cosmopark @endif">
    <link rel="icon" href="{{ $faviconPath }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ $faviconPath }}" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="google-site-verification" content="" />
    @verbatim
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('assets/images/dev/Thumbnail.png') }}"
        }
        </script>
    @endverbatim

    @stack('meta')

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->

    {{-- styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite('resources/assets/frontend/css/styles.css')
    @vite('resources/assets/frontend/css/header.css')

    @stack('styles')

    {{-- end styles --}}

    <style>
        /* Language Switcher Styles */
        .language-switcher {
            position: relative;
        }

        .language-flag {
            display: inline-block;
            transition: all 0.3s ease;
            border-radius: 4px;
            overflow: hidden;
        }

        .language-flag:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .language-flag img {
            width: 24px;
            height: 16px;
            object-fit: cover;
            border-radius: 2px;
        }

        /* Mobile Language Switcher */
        .mobile-language-switcher {
            margin-bottom: 10px;
        }

        .mobile-language-switcher .language-flag {
            display: inline-block;
            margin-right: 10px;
        }

        .mobile-language-switcher .language-flag img {
            width: 28px;
            height: 18px;
        }

        /* Flag icon existing styles */
        .flag-icon {
            width: 24px;
            height: 16px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <header class="header-main" id="header">
        <div class="container-custom">
            <div class="header-custom">
                <!-- Logo -->
                <img src="{{ $logoPath }}" alt="Logo" style="margin-right: 20px;" height="30px">
                <div class="d-flex align-items-center">
                    
                    <div class="header-nav d-none d-xl-flex align-items-center">
                        
                        
                        <!-- Time Display -->
                        <div class="time-display">
                            <div class="clock-icon">🕐</div>
                            <span id="currentTime">13:46:18</span>
                        </div>
                        
                        <a href="#" class="{{ Route::currentRouteNamed('today') ? 'active' : '' }}">LỊCH ÂM HÔM NAY</a>
                        <a href="#" class="{{ Route::currentRouteNamed('convert') ? 'active' : '' }}">ĐỔI NGÀY ÂM DƯƠNG</a>
                        <a href="#" class="{{ Route::currentRouteNamed('month') ? 'active' : '' }}">LỊCH THÁNG <span class="dropdown-indicator">▼</span></a>
                        <a href="#" class="{{ Route::currentRouteNamed('year') ? 'active' : '' }}">LỊCH NĂM <span class="dropdown-indicator">▼</span></a>
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
            <img src="{{ $logoPath }}" alt="Logo" height="40px">
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
            <li><a href="{{ route('home') }}"
                    class="{{ Route::currentRouteNamed('home') ? 'active' : '' }}">XEM LỊCH ÂM.COM</a>
            </li>
            <li><a href="#" class="{{ Route::currentRouteNamed('today') ? 'active' : '' }}">LỊCH ÂM HÔM NAY</a></li>
            <li><a href="#" class="{{ Route::currentRouteNamed('convert') ? 'active' : '' }}">ĐỔI NGÀY ÂM DƯƠNG</a></li>
            <li><a href="#" class="{{ Route::currentRouteNamed('month') ? 'active' : '' }}">LỊCH THÁNG <span class="dropdown-indicator">▼</span></a></li>
            <li><a href="#" class="{{ Route::currentRouteNamed('year') ? 'active' : '' }}">LỊCH NĂM <span class="dropdown-indicator">▼</span></a></li>
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
            window.addEventListener('scroll', requestTick, { passive: true });

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

            // Close menu when clicking on navigation links
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-list a');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
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
                anchor.addEventListener('click', function (e) {
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
