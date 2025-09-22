<footer class="footer-section animate-on-scroll">
    <div class="container-custom">
        <div class="row g-4">
            <div class="col-6 col-md-4">
                <div class="footer-column">
                    <h3>LỊCH ÂM</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Âm lịch hôm nay</a></li>
                        <li><a href="{{ route('converter') }}">Đổi ngày âm dương</a></li>
                        <li><a href="{{ route('calendar.year', ['year' => date('Y')]) }}">Lịch âm {{ date('Y') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="footer-column">
                    <h3>LỊCH VẠN NIÊN</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Lịch vạn niên hôm nay</a></li>
                        <li><a href="{{ route('calendar.year', ['year' => date('Y') + 1]) }}">Lịch vạn niên {{ date('Y') + 1 }}</a></li>
                        <li><a href="{{ route('calendar.year', ['year' => date('Y') + 2]) }}">Lịch vạn niên {{ date('Y') + 2 }}</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="footer-column">
                    <h3>KẾT NỐI</h3>
                    <div class="social-links d-flex gap-3 flex-wrap">
                        @if($socials && $socials->count() > 0)
                            @foreach($socials as $social)
                                <a href="{{ $social->url }}" target="_blank" class="social-link" title="{{ $social->name }}">
                                    @if($social->icon)
                                        <i class="{{ $social->icon }}"></i>
                                    @else
                                        <i class="fas fa-link"></i>
                                    @endif
                                    {{ $social->name }}
                                </a>
                            @endforeach
                        @else
                            <a href="#" class="social-link"> <i class="fa-brands fa-zalo"></i> Zalo</a>
                            <a href="#" class="social-link"> <i class="fa-brands fa-facebook"></i> Facebook</a>
                            <a href="#" class="social-link"> <i class="fa-brands fa-twitter"></i> Twitter</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
            <div class="footer-text flex-grow-1 me-3">
                {{ config('app.name') }} là trang cung cấp thông tin về lịch âm, lịch vạn niên, chính xác nhất và miễn phí cho
                người Việt Nam.
            </div>
            
        </div>
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@stack('scripts')

{{-- Dynamic Footer Scripts from Database --}}
@if($footerMetaTags)
    @if($footerMetaTags->gtag_code)
        {!! $footerMetaTags->getFormattedGtagCode() !!}
    @endif
    @if($footerMetaTags->custom_scripts)
        {!! $footerMetaTags->getFormattedCustomScripts() !!}
    @endif
@endif

<script>
    // Intersection Observer for footer animations
    const footerObserverOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, footerObserverOptions);

    // Observe footer elements
    document.addEventListener('DOMContentLoaded', function() {
        const footerSection = document.querySelector('.footer-section.animate-on-scroll');
        if (footerSection) {
            footerObserver.observe(footerSection);
        }

        // Add smooth scroll for footer links
        document.querySelectorAll('.footer-column a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Add click animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Add hover effects for social links
        document.querySelectorAll('.social-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

</body>

</html>
