@include('client.layouts.partials.header')

@include('components.toast-main')
@include('components.toast')

@yield('content')
@include('components.top_button')

@include('client.layouts.partials.footer')
