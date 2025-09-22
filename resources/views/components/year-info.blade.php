@props(['yearMainText' => '', 'sexagenary' => []])

@push('styles')
    @vite('resources/assets/frontend/css/components/year-info.css')
@endpush

@if (!empty($yearMainText))
<div class="year-info-section">
    <div class="year-info-content">
        <div class="year-main-text">
            <h4 class="main-text-title">Năm {{ $sexagenary['year']['combined'] ?? 'Không có thông tin' }}
                (Âm Lịch)</h4>
            <div class="main-text-content">
                <p>{{ $yearMainText }}</p>
            </div>
        </div>
    </div>
</div>
@endif
