@props(['goodDays' => [], 'badDays' => [], 'year', 'month'])

@push('styles')
    @vite('resources/assets/frontend/css/components/month-fortune.css')
@endpush

<div class="month-fortune-section">
    <div class="fortune-header">
        <h3 class="fortune-title">Ngày tốt xấu tháng {{ $month }}</h3>
        <div class="fortune-legend">
            <div class="legend-item">
                <div class="legend-dot good"></div>
                <span>Ngày tốt (Hoàng Đạo)</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot bad"></div>
                <span>Ngày xấu (Hắc Đạo)</span>
            </div>
        </div>
    </div>
    
    <div class="fortune-content">
        <div class="fortune-columns">
            <!-- Ngày tốt -->
            <div class="fortune-column good-days">
                <h4 class="column-title">Ngày tốt tháng {{ $month }} (Hoàng Đạo)</h4>
                <div class="days-list">
                    @forelse($goodDays as $day)
                        <a href="{{ route('calendar.day', [$year, $month, $day]) }}" class="day-item good">
                            Ngày {{ $day }} tháng {{ $month }} năm {{ $year }}
                        </a>
                    @empty
                        <div class="no-days">Không có ngày tốt</div>
                    @endforelse
                </div>
            </div>
            
            <!-- Ngày xấu -->
            <div class="fortune-column bad-days">
                <h4 class="column-title">Ngày xấu tháng {{ $month }} (Hắc Đạo)</h4>
                <div class="days-list">
                    @forelse($badDays as $day)
                        <a href="{{ route('calendar.day', [$year, $month, $day]) }}" class="day-item bad">
                            Ngày {{ $day }} tháng {{ $month }} năm {{ $year }}
                        </a>
                    @empty
                        <div class="no-days">Không có ngày xấu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
