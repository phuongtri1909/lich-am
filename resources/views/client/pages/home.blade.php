@extends('client.layouts.app')
@section('title', 'Lịch âm hôm nay - Xem lịch âm')
@section('description', 'Xem lịch âm hôm nay, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam')
@section('keywords', 'lịch âm, lịch âm hôm nay, giờ hoàng đạo, ngày tốt xấu, âm lịch')

@section('content')
    <div class="lunar-calendar-container">
        <div class="container">

        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/home.css')
@endpush

@push('scripts')
    <script></script>
@endpush
