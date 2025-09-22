@props(['monthCalendar', 'year', 'month', 'selectedDay' => null])

@php
    $currentYear = date('Y');
    $currentMonth = date('m');
    $currentDay = date('d');
    $isCurrentMonth = ($year == $currentYear && $month == $currentMonth);
@endphp

@push('styles')
    @vite('resources/assets/frontend/css/components/monthly-calendar.css')
@endpush

<div class="calendar-container">
    <!-- Calendar Header -->
    <div class="calendar-header">
        <div class="calendar-nav">
            <button class="nav-btn prev-btn" onclick="navigateMonth(-1)">‹</button>
            <span class="month-year">THÁNG {{ $month }} - {{ $year }}</span>
            <button class="nav-btn next-btn" onclick="navigateMonth(1)">›</button>
        </div>
    </div>

    <!-- Calendar Days Header -->
    <div class="calendar-days-header">
        <div class="day-header">Thứ hai</div>
        <div class="day-header">Thứ ba</div>
        <div class="day-header">Thứ tư</div>
        <div class="day-header">Thứ năm</div>
        <div class="day-header">Thứ sáu</div>
        <div class="day-header">Thứ bảy</div>
        <div class="day-header">Chủ nhật</div>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid">
        @foreach ($monthCalendar as $day)
                <div
                    class="calendar-day {{ $day['is_today'] ? 'today' : '' }} {{ $isCurrentMonth && $day['gregorian_day'] == $currentDay ? 'today' : '' }} {{ $selectedDay && $day['gregorian_day'] == $selectedDay ? 'selected' : '' }} {{ $day['is_good_day'] ? 'good' : ($day['is_bad_day'] ? 'bad' : 'normal') }} {{ isset($day['is_prev_month']) && $day['is_prev_month'] ? 'prev-month' : '' }} {{ isset($day['is_next_month']) && $day['is_next_month'] ? 'next-month' : '' }}"
                    onclick="selectDate({{ $day['gregorian_day'] }})">
                <div class="day-content">
                    <div class="gregorian-day">{{ $day['gregorian_day'] }}</div>
                    <div class="lunar-day">{{ $day['lunar_day'] }}</div>
                    <div class="day-name">{{ $day['sexagenary'] }}</div>
                </div>
                @if ($day['is_good_day'])
                    <div class="auspicious-dot good"></div>
                @elseif ($day['is_bad_day'])
                    <div class="auspicious-dot bad"></div>
                @else
                    <div class="auspicious-dot normal"></div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="calendar-legend">
        <div class="legend-item">
            <div class="legend-dot good"></div>
            <span>Ngày hoàng đạo</span>
        </div>
        <div class="legend-item">
            <div class="legend-dot bad"></div>
            <span>Ngày hắc đạo</span>
        </div>
        <div class="legend-item">
            <div class="legend-dot normal"></div>
            <span>Ngày bình thường</span>
        </div>
    </div>
</div>
