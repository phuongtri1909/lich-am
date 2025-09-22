@props(['currentYear'])

@push('styles')
    @vite('resources/assets/frontend/css/components/other-years.css')
@endpush

<div class="other-years-section">
    <div class="other-years-header">
        <h3 class="other-years-title">Xem lịch âm các năm khác</h3>
        <p class="other-years-subtitle">Chọn năm để xem lịch âm chi tiết</p>
    </div>
    
    <div class="other-years-content">
        <div class="years-grid">
            @for($year = $currentYear - 5; $year <= $currentYear + 6; $year++)
                <a href="{{ route('calendar.year', [$year]) }}" 
                   class="year-card {{ $year == $currentYear ? 'current-year' : '' }}">
                    <div class="year-number">{{ $year }}</div>
                    <div class="year-label">Năm {{ $year }}</div>
                    @if($year == $currentYear)
                        <div class="current-badge">Năm hiện tại</div>
                    @endif
                </a>
            @endfor
        </div>
    </div>
</div>
