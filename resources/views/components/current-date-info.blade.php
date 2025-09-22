@props(['lunarInfo', 'sexagenary', 'dayFortune', 'auspiciousHours', 'dateLabel' => 'Ngày Dương Lịch:', 'dateValue' => null])

<div class="current-date-section rounded-4">
    <div class="container-custom">
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="date-info-left">
                    <div class="date-item">
                        <div class="date-icon"><i class="fas fa-calendar-day"></i></div>
                        <div class="date-details">
                            <span class="date-label">{{ $dateLabel }}</span>
                            <span class="date-value gregorian">{{ $dateValue ?? date('d-m-Y') }}</span>
                        </div>
                    </div>
                    <div class="date-item">
                        <div class="date-icon"><i class="fas fa-moon"></i></div>
                        <div class="date-details">
                            <span class="date-label">Ngày Âm Lịch:</span>
                            <span class="date-value lunar">{{ $lunarInfo['lunar_date'] }}</span>
                        </div>
                    </div>
                    <div class="date-item">
                        <div class="date-icon"><i class="fas fa-calendar-week"></i></div>
                        <div class="date-details">
                            <span class="date-label">Ngày trong tuần:</span>
                            <span class="date-value">{{ $lunarInfo['day_of_week'] }}</span>
                        </div>
                    </div>
                    <div class="date-item">
                        <div class="date-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="date-details">
                            <span class="date-label">Ngày <span class="date-value">{{ $sexagenary['day']['combined'] }}</span> tháng
                                <span class="date-value">{{ $sexagenary['month']['combined'] }}</span> năm
                                <span class="date-value">{{ $sexagenary['year']['combined'] }}</span>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="date-info-right">
                    <div class="fortune-info rounded-4">
                        <div class="fortune-title">Ngày <span class="fw-bold">{{ $dayFortune['fortune'] }}</span>:</div>
                        <div class="fortune-desc">{{ $dayFortune['description'] }}</div>
                    </div>
                    <div class="auspicious-hours rounded-4">
                        <div class="hours-title">Giờ Hoàng Đạo:</div>
                        <div class="hours-list">
                            @if(is_array($auspiciousHours))
                                @foreach ($auspiciousHours as $hour)
                                    <span class="hour-item">{{ $hour }}</span>
                                @endforeach
                            @else
                                <span class="hour-item">{{ $auspiciousHours }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
