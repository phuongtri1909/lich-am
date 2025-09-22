@props(['currentYear', 'currentMonth'])

@push('styles')
    @vite('resources/assets/frontend/css/components/other-months.css')
@endpush

<div class="other-months-section">
    <div class="other-months-header">
        <h3 class="other-months-title">Xem lịch âm các tháng khác</h3>
        <p class="other-months-subtitle">Chọn tháng để xem lịch âm chi tiết</p>
    </div>
    
    <div class="other-months-content">
        <div class="months-grid">
            @for($month = 1; $month <= 12; $month++)
                <a href="{{ route('calendar.month', [$currentYear, $month]) }}" 
                   class="month-card {{ $month == $currentMonth ? 'current-month' : '' }}">
                    <div class="month-number">{{ $month }}</div>
                    <div class="month-name">{{ getMonthName($month) }}</div>
                </a>
            @endfor
        </div>
        
        <!-- Year Navigation -->
        <div class="year-navigation">
            <a href="{{ route('calendar.month', [$currentYear - 1, $currentMonth]) }}" class="year-nav-btn prev-year">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                    <rect width="256" height="256" fill="none"></rect>
                    <polyline points="160,208 80,128 160,48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                </svg>
                Năm {{ $currentYear - 1 }}
            </a>
            
            <div class="current-year">{{ $currentYear }}</div>
            
            <a href="{{ route('calendar.month', [$currentYear + 1, $currentMonth]) }}" class="year-nav-btn next-year">
                Năm {{ $currentYear + 1 }}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                    <rect width="256" height="256" fill="none"></rect>
                    <polyline points="96,48 176,128 96,208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                </svg>
            </a>
        </div>
    </div>
</div>

@php
    function getMonthName($month) {
        $months = [
            1 => 'Tháng Giêng',
            2 => 'Tháng Hai', 
            3 => 'Tháng Ba',
            4 => 'Tháng Tư',
            5 => 'Tháng Năm',
            6 => 'Tháng Sáu',
            7 => 'Tháng Bảy',
            8 => 'Tháng Tám',
            9 => 'Tháng Chín',
            10 => 'Tháng Mười',
            11 => 'Tháng Mười Một',
            12 => 'Tháng Chạp'
        ];
        return $months[$month] ?? '';
    }
@endphp
