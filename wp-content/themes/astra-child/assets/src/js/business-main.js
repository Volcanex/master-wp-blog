/**
 * Main JavaScript for Business Template
 *
 * This file contains the main JavaScript functionality for the business template.
 * It's compiled by Webpack and included on all frontend pages.
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

// Import SCSS for webpack processing
import '../scss/main.scss';

// Import utilities and modules
import { businessContactForms } from './modules/contact-forms';
import { businessNavigation } from './modules/navigation';
import { businessAnimations } from './modules/animations';
import { businessPerformance } from './modules/performance';

/**
 * Main Business Template Class
 */
class BusinessTemplate {
    constructor() {
        this.init();
    }

    /**
     * Initialize the business template
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }

        // Wait for window to load
        window.addEventListener('load', () => this.onLoad());
    }

    /**
     * DOM Ready handler
     */
    onReady() {
        console.log('Business Template: DOM Ready');

        // Initialize modules
        this.initModules();

        // Set up event listeners
        this.setupEventListeners();

        // Initialize business features
        this.initBusinessFeatures();
    }

    /**
     * Window Load handler
     */
    onLoad() {
        console.log('Business Template: Window Loaded');

        // Remove loading states
        document.body.classList.remove('loading');

        // Initialize performance optimizations
        businessPerformance.init();

        // Trigger any load-dependent animations
        businessAnimations.triggerLoadAnimations();
    }

    /**
     * Initialize all modules
     */
    initModules() {
        businessContactForms.init();
        businessNavigation.init();
        businessAnimations.init();
    }

    /**
     * Set up global event listeners
     */
    setupEventListeners() {
        // Handle smooth scrolling for anchor links
        this.setupSmoothScrolling();

        // Handle mobile menu toggling
        this.setupMobileMenu();

        // Handle business-specific interactions
        this.setupBusinessInteractions();

        // Handle scroll events
        this.setupScrollEvents();
    }

    /**
     * Setup smooth scrolling for internal links
     */
    setupSmoothScrolling() {
        document.querySelectorAll('a[href*="#"]:not([href="#"])').forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('href').split('#')[1];
                const targetElement = document.getElementById(targetId);

                if (targetElement && window.location.pathname === link.pathname) {
                    e.preventDefault();

                    const headerOffset = 100; // Account for fixed header
                    const targetPosition = targetElement.offsetTop - headerOffset;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL hash without jumping
                    history.pushState(null, null, `#${targetId}`);
                }
            });
        });
    }

    /**
     * Setup mobile menu functionality
     */
    setupMobileMenu() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle, .menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu, .main-navigation');

        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.contains('active');

                if (isOpen) {
                    mobileMenu.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                } else {
                    mobileMenu.classList.add('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'true');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }

    /**
     * Setup business-specific interactions
     */
    setupBusinessInteractions() {
        // Phone number click tracking
        document.querySelectorAll('a[href^="tel:"]').forEach(link => {
            link.addEventListener('click', () => {
                this.trackEvent('phone_call', {
                    phone_number: link.getAttribute('href').replace('tel:', '')
                });
            });
        });

        // Email click tracking
        document.querySelectorAll('a[href^="mailto:"]').forEach(link => {
            link.addEventListener('click', () => {
                this.trackEvent('email_click', {
                    email_address: link.getAttribute('href').replace('mailto:', '')
                });
            });
        });

        // Social media click tracking
        document.querySelectorAll('.social-media a, .business-social a').forEach(link => {
            link.addEventListener('click', () => {
                const href = link.getAttribute('href');
                let platform = 'unknown';

                if (href.includes('facebook')) platform = 'Facebook';
                else if (href.includes('twitter')) platform = 'Twitter';
                else if (href.includes('linkedin')) platform = 'LinkedIn';
                else if (href.includes('instagram')) platform = 'Instagram';

                this.trackEvent('social_click', {
                    platform: platform,
                    url: href
                });
            });
        });
    }

    /**
     * Setup scroll-based events
     */
    setupScrollEvents() {
        let ticking = false;

        const handleScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.onScroll();
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
    }

    /**
     * Handle scroll events
     */
    onScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // Header sticky behavior
        this.handleStickyHeader(scrollTop);

        // Trigger scroll animations
        businessAnimations.onScroll(scrollTop);

        // Show/hide scroll to top button
        this.handleScrollToTop(scrollTop);
    }

    /**
     * Handle sticky header behavior
     */
    handleStickyHeader(scrollTop) {
        const header = document.querySelector('.site-header');
        if (header) {
            const headerHeight = header.offsetHeight;

            if (scrollTop > headerHeight) {
                header.classList.add('sticky-active');
                document.body.style.paddingTop = `${headerHeight}px`;
            } else {
                header.classList.remove('sticky-active');
                document.body.style.paddingTop = '0';
            }
        }
    }

    /**
     * Handle scroll to top button
     */
    handleScrollToTop(scrollTop) {
        const scrollToTopBtn = document.querySelector('.scroll-to-top');
        if (scrollToTopBtn) {
            if (scrollTop > 500) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        }
    }

    /**
     * Initialize business-specific features
     */
    initBusinessFeatures() {
        // Cookie consent
        this.initCookieConsent();

        // Business hours display
        this.initBusinessHours();

        // FAQ accordions
        this.initFAQAccordions();

        // Testimonials carousel
        this.initTestimonialsCarousel();
    }

    /**
     * Initialize cookie consent
     */
    initCookieConsent() {
        if (!localStorage.getItem('cookieConsent')) {
            const banner = document.createElement('div');
            banner.className = 'cookie-consent-banner';
            banner.innerHTML = `
                <div class="cookie-content">
                    <p>We use cookies to improve your experience. By continuing to browse, you agree to our cookie policy.</p>
                    <button class="cookie-accept-btn">Accept</button>
                </div>
            `;

            document.body.appendChild(banner);

            banner.querySelector('.cookie-accept-btn').addEventListener('click', () => {
                localStorage.setItem('cookieConsent', 'accepted');
                banner.style.display = 'none';
            });
        }
    }

    /**
     * Initialize business hours display
     */
    initBusinessHours() {
        const businessHours = document.querySelector('.business-hours');
        if (businessHours) {
            const now = new Date();
            const currentDay = now.getDay();
            const currentDayElement = businessHours.querySelector(`.day-${currentDay}`);

            if (currentDayElement) {
                currentDayElement.classList.add('today');
            }
        }
    }

    /**
     * Initialize FAQ accordions
     */
    initFAQAccordions() {
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });

                // If this item wasn't active, open it
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    }

    /**
     * Initialize testimonials carousel
     */
    initTestimonialsCarousel() {
        const testimonials = document.querySelector('.business-testimonials');
        if (testimonials) {
            const items = testimonials.querySelectorAll('.testimonial-item');
            if (items.length > 1) {
                let currentIndex = 0;

                const showTestimonial = (index) => {
                    items.forEach((item, i) => {
                        item.classList.toggle('active', i === index);
                    });
                };

                // Auto-rotate testimonials
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % items.length;
                    showTestimonial(currentIndex);
                }, 5000);

                // Initialize first testimonial
                showTestimonial(0);
            }
        }
    }

    /**
     * Track events (Google Analytics, etc.)
     */
    trackEvent(eventName, parameters = {}) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                event_category: 'Business Template',
                ...parameters
            });
        }

        // Console log for development
        if (window.WP_LOCAL_DEV) {
            console.log('Event tracked:', eventName, parameters);
        }
    }
}

// Initialize the business template
const businessTemplate = new BusinessTemplate();

// Make available globally for other scripts
window.BusinessTemplate = businessTemplate;

// Export for module usage
export default BusinessTemplate;