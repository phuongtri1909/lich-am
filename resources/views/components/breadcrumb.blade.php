@props(['items' => []])

@push('styles')
    @vite('resources/assets/frontend/css/components/breadcrumb.css')
@endpush

<nav class="breadcrumb-nav mt-0" aria-label="Breadcrumb">
    <ul class="breadcrumb-list">
        @foreach($items as $index => $item)
            <li class="breadcrumb-item {{ $index === count($items) - 1 ? 'active' : '' }}">
                @if($index === count($items) - 1)
                    <span class="breadcrumb-current">
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ $item['url'] ?? '#' }}" class="breadcrumb-link">
                        <span class="breadcrumb-content">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
