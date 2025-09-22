@props(['solarHolidays' => [], 'lunarHolidays' => [], 'year'])

@push('styles')
    @vite('resources/assets/frontend/css/components/year-events.css')
@endpush

@if(!empty($solarHolidays) || !empty($lunarHolidays))
<div class="year-events-section">
    <div class="events-content">
        <div class="events-columns">
            @if(!empty($solarHolidays))
            <!-- Ngày lễ dương lịch -->
            <div class="events-column solar-holidays-column">
                <h4 class="events-title">Ngày lễ dương lịch năm {{ $year }}</h4>
                <div class="events-list">
                    @foreach($solarHolidays as $holiday)
                        <div class="event-item solar-holiday-item">
                            <div class="event-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none"></rect>
                                    <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle>
                                    <circle cx="128" cy="128" r="40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle>
                                    <path d="M63.79905,199.37405a72.02812,72.02812,0,0,1,128.40177-.00026" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path>
                                </svg>
                            </div>
                            <span class="event-text">{{ $holiday['day'] }}/{{ $holiday['month'] }}: {{ $holiday['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            @if(!empty($lunarHolidays))
            <!-- Ngày lễ âm lịch -->
            <div class="events-column lunar-holidays-column">
                <h4 class="events-title">Ngày lễ âm lịch năm {{ $year }}</h4>
                <div class="events-list">
                    @foreach($lunarHolidays as $holiday)
                        <div class="event-item lunar-holiday-item">
                            <div class="event-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none"></rect>
                                    <path d="M224,177.32122V78.67878a8,8,0,0,0-4.07791-6.9726l-88-49.5a8,8,0,0,0-7.84418,0l-88,49.5A8,8,0,0,0,32,78.67878v98.64244a8,8,0,0,0,4.07791,6.9726l88,49.5a8,8,0,0,0,7.84418,0l88-49.5A8,8,0,0,0,224,177.32122Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path>
                                    <polyline points="177.022 152.511 177.022 100.511 80 47" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                    <polyline points="222.897 74.627 128.949 128 33.108 74.617" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                                    <line x1="128.94915" y1="128" x2="128.01036" y2="234.82131" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                </svg>
                            </div>
                            <span class="event-text">{{ $holiday['day'] }}/{{ $holiday['month'] }}: {{ $holiday['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
