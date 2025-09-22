@extends('client.layouts.app')
@section('title', "Lịch âm năm $year - Xem lịch âm")
@section('description', "Xem lịch âm năm $year, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam")
@section('keywords', 'lịch âm, lịch âm năm, giờ hoàng đạo, ngày tốt xấu, âm lịch')

@section('content')
    <div class="lunar-calendar-container">
        <div class="container-custom">
            <!-- Main Title -->
            <div class="main-title-section">
                <div class="container-custom">
                    <h1 class="main-title">Lịch âm năm {{ $year }}</h1>
                    <button class="view-lunar-btn">lịch vạn niên năm {{ $year }}</button>
                </div>
            </div>
                
            <!-- Year Info Section -->
            <x-year-info 
                :yearMainText="$yearMainText"
                :sexagenary="$sexagenary" />
        </div>

        <!-- Main Content Layout -->
        <div class="main-content-layout mt-4">
            <div class="container-custom">
                <x-breadcrumb :items="[
                    ['label' => 'Trang chủ', 'url' => route('home')],
                    ['label' => 'Năm ' . $year, 'url' => route('calendar.year', [$year])],
                ]" />
                <div class="row g-4 pt-1">
                    <!-- Year Calendar Grid -->
                    <div class="col-12">
                        <x-year-calendar 
                            :yearCalendars="$yearCalendars"
                            :year="$year"
                            :currentMonth="$currentMonth"
                            :currentDay="$currentDay" />
                    </div>
                    
                    <!-- Other Years Section -->
                    <div class="col-12">
                        <x-other-years 
                            :currentYear="$year" />
                    </div>

                      <!-- Year Events Section -->
                      <div class="col-12">
                        <x-year-events 
                            :solarHolidays="$solarHolidays"
                            :lunarHolidays="$lunarHolidays"
                            :year="$year" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/home.css')
@endpush

@push('scripts')
    <script>
        // Animation on scroll
        const yearObserverOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const yearObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                }
            });
        }, yearObserverOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
            yearObserver.observe(el);
        });
    </script>
@endpush
