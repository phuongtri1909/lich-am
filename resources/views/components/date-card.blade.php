@props(['lunarInfo', 'sexagenary', 'solarTerm', 'year', 'month', 'day', 'isToday' => false, 'auspiciousHours'])

@push('styles')
    @vite('resources/assets/frontend/css/components/date-card.css')
@endpush

<div class="date-card-container">
    <!-- Header Section -->
    <div class="date-card-header">
        <div class="header-info">
            <span class="month-text">THÁNG {{ $month }}</span>
            <span class="year-text">{{ $year }}</span>
            <span class="day-text">{{ $lunarInfo['day_of_week'] }}</span>
        </div>
    </div>

    <!-- Top Section -->
    <div class="date-card-top d-flex justify-content-between align-items-center">
        <div class="today-button {{ $isToday ? 'active' : '' }}">
            <i class="fas fa-check"></i>
            HÔM NAY
        </div>
        <div class="week-info d-flex gap-3">
            <span>Tuần {{ date('W', strtotime($year . '-' . $month . '-' . $day)) }}</span>
            <span>Ngày {{ date('z', strtotime($year . '-' . $month . '-' . $day)) + 1 }}</span>
        </div>
    </div>

    <!-- Main Date Display -->
    <div class="main-date-display d-flex align-items-center justify-content-center">
        <button class="nav-arrow nav-prev" onclick="navigateDay(-1)">‹</button>
        <div class="main-date-number">{{ $day }}</div>
        <button class="nav-arrow nav-next" onclick="navigateDay(1)">›</button>
    </div>

    <!-- Quote Section -->
    <div class="quote-section d-flex">
        <div class="quote-bracket">┃</div>
        <div class="quote-content">
            <div class="quote-text">Giờ Hoàng Đạo (Giờ Tốt)</div>
            <div class="quote-principle">
                @if (is_array($auspiciousHours))
                    @foreach ($auspiciousHours as $hour)
                        <span class="hour-item">{{ $hour }}</span>
                    @endforeach
                @else
                    <span class="hour-item">{{ $auspiciousHours }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Solar Term Info -->
    <div class="solar-term-info d-flex justify-content-between align-items-center">
        <div class="solar-term-text">
            Tiết <span class="solar-term-name">{{ $solarTerm['name'] }}</span> | Bắt đầu vào
            {{ date('d-m', strtotime($solarTerm['begin_date'])) }}
        </div>
        <div class="moon-icon">🌙+</div>
    </div>

    <!-- Bottom Info Grid -->
    <div class="info-grid row g-3">
        <div class="info-column col-4 text-center">
            <div class="info-item">Ngày {{ $sexagenary['day']['combined'] }}</div>
            <div class="info-item">Tháng {{ $sexagenary['month']['combined'] }}</div>
            <div class="info-item">Năm {{ $sexagenary['year']['combined'] }}</div>
        </div>
        <div class="info-column center col-4 text-center">
            <div class="info-item">Tháng
                {{ $lunarInfo['lunar_date'] ? explode('-', $lunarInfo['lunar_date'])[1] : $month }}
                ({{ $sexagenary['month']['combined'] }})
            </div>
            <div class="lunar-day-number">
                {{ $lunarInfo['lunar_date'] ? explode('-', $lunarInfo['lunar_date'])[0] : $day }}
            </div>
            <div class="info-item">Năm {{ $year }} ({{ $sexagenary['year']['combined'] }})</div>
        </div>
        <div class="info-column col-4 text-center">
            <div class="info-item">Giờ {{ $sexagenary['hour']['combined'] }}</div>
            <div class="info-item">Tuần {{ $sexagenary['week']['combined'] }}</div>
        </div>
    </div>
</div>
