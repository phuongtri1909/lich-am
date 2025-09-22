@props(['yearCalendars' => [], 'year', 'currentMonth', 'currentDay'])

@push('styles')
    @vite('resources/assets/frontend/css/components/year-calendar.css')
@endpush

<div class="year-calendar-container">
    <div class="year-header">
        <h2>Lịch năm {{ $year }}</h2>
        <div class="year-nav">
            <a href="{{ route('calendar.year', [$year - 1]) }}" class="nav-btn prev-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                    <rect width="256" height="256" fill="none"></rect>
                    <polyline points="160,208 80,128 160,48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                </svg>
                Năm trước
            </a>
            <a href="{{ route('calendar.year', [$year + 1]) }}" class="nav-btn next-btn">
                Năm sau
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                    <rect width="256" height="256" fill="none"></rect>
                    <polyline points="96,48 176,128 96,208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                </svg>
            </a>
        </div>
    </div>
    
    <div class="year-grid">
        @for($month = 1; $month <= 12; $month++)
            <div class="year-month-card">
                <div class="month-header">
                    <a href="{{ route('calendar.month', ['year' => $year, 'month' => $month]) }}" class="month-link">
                        <h3>Tháng {{ $month }}</h3>
                    </a>
                </div>
                
                <!-- Calendar Days Header -->
                <div class="calendar-days-header">
                    <div class="day-header">T2</div>
                    <div class="day-header">T3</div>
                    <div class="day-header">T4</div>
                    <div class="day-header">T5</div>
                    <div class="day-header">T6</div>
                    <div class="day-header">T7</div>
                    <div class="day-header">CN</div>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-grid year-month-grid">
                    @foreach ($yearCalendars[$month] as $day)
                        <div
                            class="calendar-day {{ $day['is_today'] ? 'today' : '' }} {{ $day['is_good_day'] ? 'good' : ($day['is_bad_day'] ? 'bad' : 'normal') }} {{ isset($day['is_prev_month']) && $day['is_prev_month'] ? 'prev-month' : '' }} {{ isset($day['is_next_month']) && $day['is_next_month'] ? 'next-month' : '' }} {{ $month == $currentMonth && $day['gregorian_day'] == $currentDay ? 'current-day' : '' }}"
                            onclick="selectDate({{ $day['gregorian_day'] }}, {{ $month }})">
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
            </div>
        @endfor
    </div>
</div>

<script>
    function selectDate(day, month) {
        window.location.href = `/calendar/{{ $year }}/${month}/${day}`;
    }
</script>
