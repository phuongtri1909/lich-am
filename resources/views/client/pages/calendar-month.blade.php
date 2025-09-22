@extends('client.layouts.app')
@section('title', "Lịch âm tháng $month/$year - Xem lịch âm")
@section('description',
    "Xem lịch âm tháng $month/$year, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt
    Nam")
@section('keywords', 'lịch âm, lịch âm tháng, giờ hoàng đạo, ngày tốt xấu, âm lịch')

@section('content')
    <div class="lunar-calendar-container">
        <div class="container-custom">
            <!-- Main Title -->
            <div class="main-title-section">
                <div class="container-custom">
                    <h1 class="main-title">Lịch âm tháng {{ $month }} năm {{ $year }}</h1>
                    <button class="view-lunar-btn">lịch vạn niên tháng {{ $month }} năm {{ $year }}</button>
                </div>
            </div>

            <!-- Month Info Section -->
            <div class="month-info-section">
                <div class="month-info-content">
                    @if (!empty($monthMainText))
                        <div class="month-main-text">
                            <h4 class="main-text-title">Tháng {{ $sexagenary['month']['combined'] ?? 'Không có thông tin' }}
                                (Âm Lịch)</h4>
                            <div class="main-text-content">
                                <p>{{ $monthMainText }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="main-content-layout">
            <div class="container-custom">
                <x-breadcrumb :items="[
                    ['label' => 'Trang chủ', 'url' => route('home')],
                    ['label' => 'Năm ' . $year, 'url' => route('calendar.year', [$year])],
                    ['label' => 'Tháng ' . $month, 'url' => route('calendar.month', [$year, $month])],
                ]" />
                <div class="row g-4 pt-1">
                    <div class="col-12">
                        <x-monthly-calendar :monthCalendar="$monthCalendar" :year="$year" :month="$month" />
                    </div>
                    
                    <!-- Month Fortune Section -->
                    <div class="col-12">
                        <x-month-fortune 
                            :goodDays="$goodDays"
                            :badDays="$badDays"
                            :year="$year"
                            :month="$month" />
                    </div>
                    
                    <!-- Month Events Section -->
                    <div class="col-12">
                        <x-month-events 
                            :solarHolidays="$solarHolidays"
                            :historicalEvents="$historicalEvents"
                            :month="$month" />
                    </div>
                    
                    <!-- Other Months Section -->
                    <div class="col-12">
                        <x-other-months 
                            :currentYear="$year"
                            :currentMonth="$month" />
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
        function navigateMonth(direction) {
            const currentDate = new Date({{ $year }}, {{ $month }} - 1, 1);
            currentDate.setMonth(currentDate.getMonth() + direction);

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;

            window.location.href = `/calendar/${year}/${month}`;
        }

        function selectDate(day) {
            window.location.href = `/calendar/{{ $year }}/{{ $month }}/${day}`;
        }

        // Animation on scroll
        const monthObserverOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const monthObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                }
            });
        }, monthObserverOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
            monthObserver.observe(el);
        });
    </script>
@endpush
