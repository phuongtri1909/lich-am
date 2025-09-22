@extends('client.layouts.app')
@section('title', "Lịch âm ngày $day/$month/$year - Xem lịch âm")
@section('description', "Xem lịch âm ngày $day/$month/$year, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam")
@section('keywords', 'lịch âm, lịch âm ngày, giờ hoàng đạo, ngày tốt xấu, âm lịch')

@section('content')
    <div class="lunar-calendar-container">
        <div class="container-custom">
            <!-- Main Title -->
            <div class="main-title-section">
                <div class="container-custom">
                    <h1 class="main-title">Lịch âm ngày {{ $day }} tháng {{ $month }} năm {{ $year }}</h1>
                    <button class="view-lunar-btn">lịch vạn niên ngày {{ $day }} tháng {{ $month }} năm {{ $year }}</button>
                </div>
            </div>

            <!-- Current Date Info -->
            <x-current-date-info 
                :lunarInfo="$lunarInfo"
                :sexagenary="$sexagenary"
                :dayFortune="$dayFortune"
                :auspiciousHours="$auspiciousHours"
                dateLabel="Ngày Dương Lịch:"
                :dateValue="$day . '-' . $month . '-' . $year" />
        </div>

        <!-- Main Content Layout -->
        <div class="main-content-layout mt-4">
            <div class="container-custom">
                <x-breadcrumb :items="[
                    ['label' => 'Trang chủ', 'url' => route('home')],
                    ['label' => 'Năm ' . $year, 'url' => route('calendar.year', [$year])],
                    ['label' => 'Tháng ' . $month, 'url' => route('calendar.month', [$year, $month])],
                    ['label' => 'Ngày ' . $day, 'url' => route('calendar.day', [$year, $month, $day])],
                ]" />
                <div class="row g-4">
                    <!-- Top Section - Large Date Display -->
                    <div class="col-12 mt-5">
                        <x-date-card 
                            :lunarInfo="$lunarInfo"
                            :sexagenary="$sexagenary"
                            :solarTerm="$solarTerm"
                            :year="$year"
                            :month="$month"
                            :day="$day"
                            :isToday="date('Y-m-d') === $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $day)" 
                            :auspiciousHours="$auspiciousHours"/>
                    </div>

                    <!-- Bottom Section - Monthly Calendar -->
                    <div class="col-12">
                        <x-monthly-calendar 
                            :monthCalendar="$monthCalendar"
                            :year="$year"
                            :month="$month"
                            :selectedDay="$day" />
                    </div>
                    
                    <!-- Fortune Detail Section -->
                    @if(!empty($vncalDetail))
                    <div class="col-12">
                        <div class="fortune-detail-section">
                            <div class="section-header">
                                <h3 class="section-title">Xem tốt xấu ngày {{ $day }} tháng {{ $month }}</h3>
                            </div>
                            <div class="fortune-detail-content">
                                {!! $vncalDetail !!}
                            </div>
                        </div>
                    </div>
                    @endif
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
        function navigateDay(direction) {
            const currentDate = new Date({{ $year }}, {{ $month }} - 1, {{ $day }});
            currentDate.setDate(currentDate.getDate() + direction);
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;
            const day = currentDate.getDate();
            
            window.location.href = `/calendar/${year}/${month}/${day}`;
        }

        function navigateDate(direction) {
            const currentDate = new Date({{ $year }}, {{ $month }} - 1, {{ $day }});
            currentDate.setDate(currentDate.getDate() + direction);
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;
            const day = currentDate.getDate();
            
            window.location.href = `/calendar/${year}/${month}/${day}`;
        }

        function navigateMonth(direction) {
            const currentDate = new Date({{ $year }}, {{ $month }} - 1, {{ $day }});
            currentDate.setMonth(currentDate.getMonth() + direction);
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;
            const day = currentDate.getDate();
            
            window.location.href = `/calendar/${year}/${month}/${day}`;
        }

        function selectDate(day) {
            window.location.href = `/calendar/{{ $year }}/{{ $month }}/${day}`;
        }

        // Animation on scroll
        const dayObserverOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const dayObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                }
            });
        }, dayObserverOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
            dayObserver.observe(el);
        });
    </script>
@endpush
