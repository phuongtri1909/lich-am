<footer class="footer-section">
    <div class="footer-background">
        
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@stack('scripts')

<script>
    // Intersection Observer for footer animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.visibility = 'visible';
            }
        });
    }, observerOptions);

    // Observe footer elements
    document.addEventListener('DOMContentLoaded', function() {
        const footerElements = document.querySelectorAll('.footer-content .animate__animated');
        footerElements.forEach(element => {
            element.style.visibility = 'hidden';
            footerObserver.observe(element);
        });

        // Add smooth scroll for footer links
        document.querySelectorAll('.footer-links a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Add click animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>

<style>
    /* Footer Animations */
    .footer-section .footer-logo.animate-on-scroll,
    .footer-section .footer-address.animate-on-scroll,
    .footer-section .footer-menu.animate-on-scroll,
    .footer-section .footer-company.animate-on-scroll,
    .footer-section .footer-social.animate-on-scroll {
        opacity: 0;
    }

    .footer-section .animate-on-scroll.animated {
        opacity: 1;
        animation: footerFadeInUp 0.7s both;
    }

    .footer-section .footer-logo.animate-on-scroll.animated {
        animation-delay: 0.1s;
    }

    .footer-section .footer-address.animate-on-scroll.animated {
        animation-delay: 0.2s;
    }

    .footer-section .footer-menu.animate-on-scroll.animated {
        animation-delay: 0.3s;
    }

    .footer-section .footer-company.animate-on-scroll.animated {
        animation-delay: 0.4s;
    }

    .footer-section .footer-social.animate-on-scroll.animated {
        animation-delay: 0.5s;
    }

    @keyframes footerFadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }
</style>
</body>

</html>
