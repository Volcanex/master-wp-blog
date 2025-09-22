/**
 * Astra Business Child Theme Custom JavaScript
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Wait for document ready
    $(document).ready(function() {

        /**
         * Business Contact Form Enhancements
         */
        $('.business-form input, .business-form textarea').on('focus', function() {
            $(this).closest('.form-group').addClass('focused');
        }).on('blur', function() {
            if (!$(this).val()) {
                $(this).closest('.form-group').removeClass('focused');
            }
        });

        /**
         * Smooth Scroll for Business Navigation
         */
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                    return false;
                }
            }
        });

        /**
         * Business Services Animation on Scroll
         */
        function animateOnScroll() {
            $('.business-card, .service-icon').each(function() {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animate-in');
                }
            });
        }

        // Initial check
        animateOnScroll();

        // Check on scroll
        $(window).scroll(function() {
            animateOnScroll();
        });

        /**
         * Business Header Sticky Behavior
         */
        var stickyHeader = $('.site-header');
        var headerHeight = stickyHeader.outerHeight();

        $(window).scroll(function() {
            if ($(window).scrollTop() > headerHeight) {
                stickyHeader.addClass('sticky-active');
                $('body').css('padding-top', headerHeight + 'px');
            } else {
                stickyHeader.removeClass('sticky-active');
                $('body').css('padding-top', '0');
            }
        });

        /**
         * Business CTA Button Hover Effects
         */
        $('.business-cta .ast-button, .business-cta .button').hover(
            function() {
                $(this).addClass('hover-effect');
            },
            function() {
                $(this).removeClass('hover-effect');
            }
        );

        /**
         * Business Testimonials Carousel (if testimonials exist)
         */
        if ($('.business-testimonials').length) {
            $('.business-testimonials').each(function() {
                var testimonialSlider = $(this);
                var testimonials = testimonialSlider.find('.testimonial-item');
                var currentIndex = 0;

                if (testimonials.length > 1) {
                    // Auto rotate testimonials
                    setInterval(function() {
                        testimonials.eq(currentIndex).removeClass('active');
                        currentIndex = (currentIndex + 1) % testimonials.length;
                        testimonials.eq(currentIndex).addClass('active');
                    }, 5000);

                    // Initialize first testimonial
                    testimonials.first().addClass('active');
                }
            });
        }

        /**
         * Business Phone Number Click Tracking (for analytics)
         */
        $('a[href^="tel:"]').click(function() {
            var phoneNumber = $(this).attr('href').replace('tel:', '');

            // Google Analytics tracking (if GA is loaded)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'phone_call', {
                    'event_category': 'Business Contact',
                    'event_label': phoneNumber
                });
            }

            // Custom analytics tracking can be added here
            console.log('Phone call initiated: ' + phoneNumber);
        });

        /**
         * Business Email Click Tracking
         */
        $('a[href^="mailto:"]').click(function() {
            var emailAddress = $(this).attr('href').replace('mailto:', '');

            // Google Analytics tracking (if GA is loaded)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'email_click', {
                    'event_category': 'Business Contact',
                    'event_label': emailAddress
                });
            }

            console.log('Email link clicked: ' + emailAddress);
        });

        /**
         * Business Form Submission Enhancements
         */
        $('.business-form').submit(function(e) {
            var form = $(this);
            var submitButton = form.find('input[type="submit"], button[type="submit"]');

            // Add loading state
            submitButton.prop('disabled', true).addClass('loading');

            // If using AJAX forms, handle the response
            // This is a placeholder for AJAX form handling
            setTimeout(function() {
                submitButton.prop('disabled', false).removeClass('loading');
            }, 2000);
        });

        /**
         * Business Services Toggle (for mobile)
         */
        $('.service-toggle').click(function() {
            var serviceDetails = $(this).next('.service-details');

            if (serviceDetails.is(':visible')) {
                serviceDetails.slideUp(300);
                $(this).removeClass('expanded');
            } else {
                serviceDetails.slideDown(300);
                $(this).addClass('expanded');
            }
        });

        /**
         * Business Map Integration Placeholder
         */
        if ($('.business-map').length) {
            // Google Maps or other map service integration would go here
            console.log('Business map container found - ready for map integration');
        }

        /**
         * Business Social Media Link Tracking
         */
        $('.social-media a, .business-social a').click(function() {
            var socialNetwork = $(this).attr('href');
            var networkName = 'unknown';

            if (socialNetwork.includes('facebook')) networkName = 'Facebook';
            else if (socialNetwork.includes('twitter')) networkName = 'Twitter';
            else if (socialNetwork.includes('linkedin')) networkName = 'LinkedIn';
            else if (socialNetwork.includes('instagram')) networkName = 'Instagram';

            // Analytics tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'social_click', {
                    'event_category': 'Social Media',
                    'event_label': networkName
                });
            }
        });

        /**
         * Business Hours Display Enhancement
         */
        if ($('.business-hours').length) {
            var now = new Date();
            var currentDay = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
            var currentTime = now.getHours() * 60 + now.getMinutes(); // Current time in minutes

            // This is a basic example - you'd customize based on actual business hours
            $('.business-hours .day-' + currentDay).addClass('today');
        }

    });

    /**
     * Window Load Events
     */
    $(window).on('load', function() {
        // Remove loading states
        $('body').removeClass('loading');

        // Trigger any animations that depend on full page load
        $('.business-hero-image').addClass('loaded');
    });

})(jQuery);

/**
 * Vanilla JavaScript functions (no jQuery dependency)
 */

/**
 * Business Cookie Consent (GDPR Compliance)
 */
document.addEventListener('DOMContentLoaded', function() {

    // Simple cookie consent implementation
    function showCookieConsent() {
        if (!localStorage.getItem('cookieConsent')) {
            var consentBanner = document.createElement('div');
            consentBanner.className = 'cookie-consent-banner';
            consentBanner.innerHTML = `
                <div class="cookie-content">
                    <p>We use cookies to improve your experience on our website. By continuing to browse, you agree to our cookie policy.</p>
                    <button onclick="acceptCookies()" class="cookie-accept-btn">Accept</button>
                </div>
            `;
            document.body.appendChild(consentBanner);
        }
    }

    // Accept cookies function
    window.acceptCookies = function() {
        localStorage.setItem('cookieConsent', 'accepted');
        var banner = document.querySelector('.cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    };

    // Show cookie consent if needed
    showCookieConsent();

    /**
     * Business Contact Form Validation
     */
    var contactForms = document.querySelectorAll('.business-form');

    contactForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var isValid = true;
            var requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            // Email validation
            var emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(function(email) {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email.value && !emailPattern.test(email.value)) {
                    email.classList.add('error');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                console.log('Form validation failed');
            }
        });
    });

});

/**
 * Performance Optimization: Lazy Loading Images
 */
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}