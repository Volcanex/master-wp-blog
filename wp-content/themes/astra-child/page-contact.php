<?php
/**
 * Template Name: Business Contact Page
 *
 * This template is designed for business contact pages
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

get_header(); ?>

<div class="ast-container">
    <div class="business-contact-page">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <!-- Contact Hero Section -->
                <header class="business-contact-hero">
                    <div class="business-container">
                        <h1 class="business-heading"><?php the_title(); ?></h1>
                        <?php if (get_the_content()) : ?>
                            <div class="contact-hero-intro">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Contact Information Section -->
        <section class="contact-info-section business-py-8">
            <div class="business-container">
                <div class="contact-info-grid">

                    <!-- Phone Contact -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Call Us</h3>
                        <?php $phone = get_business_phone(); ?>
                        <?php if ($phone) : ?>
                            <p><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></p>
                        <?php else : ?>
                            <p><a href="tel:+1-555-123-4567">+1 (555) 123-4567</a></p>
                        <?php endif; ?>
                        <span class="contact-label">Mon-Fri 9AM-6PM</span>
                    </div>

                    <!-- Email Contact -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Us</h3>
                        <?php $email = get_business_email(); ?>
                        <?php if ($email) : ?>
                            <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                        <?php else : ?>
                            <p><a href="mailto:hello@yourbusiness.com">hello@yourbusiness.com</a></p>
                        <?php endif; ?>
                        <span class="contact-label">We'll respond within 24 hours</span>
                    </div>

                    <!-- Location Contact -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Visit Us</h3>
                        <?php $address = get_business_address(); ?>
                        <?php if ($address) : ?>
                            <p><?php echo wp_kses_post($address); ?></p>
                        <?php else : ?>
                            <p>123 Business Street<br>Suite 100<br>City, State 12345</p>
                        <?php endif; ?>
                        <span class="contact-label">By appointment only</span>
                    </div>

                    <!-- Business Hours -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Business Hours</h3>
                        <?php $hours = get_business_hours(); ?>
                        <?php if ($hours) : ?>
                            <p><?php echo wp_kses_post($hours); ?></p>
                        <?php else : ?>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed</p>
                        <?php endif; ?>
                        <span class="contact-label">Emergency support available 24/7</span>
                    </div>

                </div>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="contact-form-section" style="background-color: var(--business-light);">
            <div class="business-container business-py-8">
                <div class="contact-form-wrapper">
                    <div class="contact-form-content">
                        <h2>Send Us a Message</h2>
                        <p>Have a question or want to discuss a project? Fill out the form below and we'll get back to you as soon as possible.</p>

                        <!-- Contact Form - Replace with Contact Form 7 or your preferred form plugin -->
                        <form class="business-form contact-form" method="post" action="" id="business-contact-form">
                            <div class="form-row">
                                <div class="form-group half-width">
                                    <label for="first-name">First Name *</label>
                                    <input type="text" id="first-name" name="first_name" required>
                                </div>
                                <div class="form-group half-width">
                                    <label for="last-name">Last Name *</label>
                                    <input type="text" id="last-name" name="last_name" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group half-width">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                <div class="form-group half-width">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company">Company Name</label>
                                <input type="text" id="company" name="company">
                            </div>

                            <div class="form-group">
                                <label for="service">Service Interested In</label>
                                <select id="service" name="service">
                                    <option value="">Select a service</option>
                                    <option value="web-design">Web Design</option>
                                    <option value="digital-marketing">Digital Marketing</option>
                                    <option value="seo">SEO Services</option>
                                    <option value="ecommerce">E-commerce Solutions</option>
                                    <option value="consulting">Business Consulting</option>
                                    <option value="support">Technical Support</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="budget">Project Budget</label>
                                <select id="budget" name="budget">
                                    <option value="">Select budget range</option>
                                    <option value="under-5k">Under $5,000</option>
                                    <option value="5k-10k">$5,000 - $10,000</option>
                                    <option value="10k-25k">$10,000 - $25,000</option>
                                    <option value="25k-50k">$25,000 - $50,000</option>
                                    <option value="over-50k">Over $50,000</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="6" placeholder="Tell us about your project, goals, and timeline..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="newsletter" value="1">
                                    <span class="checkmark"></span>
                                    Subscribe to our newsletter for business tips and updates
                                </label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="ast-button submit-button">
                                    <span class="button-text">Send Message</span>
                                    <span class="button-loader" style="display: none;">Sending...</span>
                                </button>
                            </div>

                            <p class="form-privacy">
                                <small>By submitting this form, you agree to our privacy policy. We will never share your information with third parties.</small>
                            </p>
                        </form>
                    </div>

                    <div class="contact-form-sidebar">
                        <div class="contact-sidebar-item">
                            <h4>Quick Response</h4>
                            <p>We typically respond to all inquiries within 2-4 business hours during regular business hours.</p>
                        </div>

                        <div class="contact-sidebar-item">
                            <h4>Free Consultation</h4>
                            <p>Schedule a complimentary 30-minute consultation to discuss your project and goals.</p>
                            <a href="#" class="consultation-link">Book Consultation</a>
                        </div>

                        <div class="contact-sidebar-item">
                            <h4>Follow Us</h4>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section (Placeholder) -->
        <section class="map-section">
            <div class="business-map" id="business-map">
                <!-- Map placeholder - replace with actual map integration -->
                <div class="map-placeholder">
                    <i class="fas fa-map" style="font-size: 80px; color: var(--business-primary); opacity: 0.3;"></i>
                    <p>Interactive Map Placeholder</p>
                    <small>Integrate with Google Maps, Mapbox, or other mapping service</small>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="contact-faq business-py-8">
            <div class="business-container">
                <h2 class="business-text-center business-mb-4">Frequently Asked Questions</h2>

                <div class="faq-accordion">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>How quickly can you start on my project?</h4>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We can typically begin new projects within 1-2 weeks, depending on our current workload and project complexity. Rush projects may be accommodated for an additional fee.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>Do you work with clients outside your local area?</h4>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! We work with clients globally. Most of our communication is done via email, video calls, and project management tools, making distance irrelevant to the quality of our service.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>What information should I include in my initial inquiry?</h4>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Please include your business goals, project timeline, budget range, and any specific requirements or preferences you have. The more details you provide, the better we can tailor our initial response to your needs.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>Do you offer ongoing support after project completion?</h4>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Absolutely! We offer various support packages ranging from basic maintenance to comprehensive growth partnerships. We'll discuss the best option for your needs during our initial consultation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
/* Contact Page Specific Styles */
.business-contact-hero {
    background: linear-gradient(135deg, var(--business-primary), var(--business-secondary));
    color: white;
    padding: 80px 0;
    text-align: center;
}

.contact-hero-intro {
    font-size: 1.1em;
    margin-top: 20px;
    opacity: 0.9;
}

.contact-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
}

.contact-info-item {
    text-align: center;
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.contact-icon {
    background: var(--business-primary);
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 24px;
}

.contact-info-item h3 {
    margin: 20px 0 15px;
    color: var(--business-dark);
}

.contact-info-item a {
    color: var(--business-primary);
    text-decoration: none;
    font-weight: 600;
}

.contact-info-item a:hover {
    text-decoration: underline;
}

.contact-label {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 10px;
}

.contact-form-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 60px;
    max-width: 1200px;
    margin: 0 auto;
}

.contact-form-content h2 {
    color: var(--business-dark);
    margin-bottom: 15px;
}

.contact-form-content p {
    color: #666;
    margin-bottom: 40px;
    line-height: 1.6;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group.half-width {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--business-dark);
    font-weight: 600;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--business-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    margin: 0;
}

.submit-button {
    background: var(--business-primary);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background: var(--business-secondary);
}

.form-privacy {
    margin-top: 20px;
    color: #666;
}

.contact-form-sidebar {
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    height: fit-content;
}

.contact-sidebar-item {
    margin-bottom: 40px;
}

.contact-sidebar-item:last-child {
    margin-bottom: 0;
}

.contact-sidebar-item h4 {
    color: var(--business-dark);
    margin-bottom: 15px;
}

.contact-sidebar-item p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.consultation-link {
    color: var(--business-primary);
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.consultation-link:hover {
    border-color: var(--business-primary);
}

.social-links {
    display: flex;
    gap: 10px;
}

.social-link {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: var(--business-primary);
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    transition: background-color 0.3s ease;
}

.social-link:hover {
    background: var(--business-secondary);
}

.map-section {
    height: 400px;
    position: relative;
}

.map-placeholder {
    height: 100%;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
}

.faq-accordion .faq-item {
    background: white;
    margin-bottom: 10px;
    border-radius: 8px;
    overflow: hidden;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    cursor: pointer;
    background: white;
    transition: background-color 0.3s ease;
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question h4 {
    margin: 0;
    color: var(--business-dark);
}

.faq-question i {
    color: var(--business-primary);
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(45deg);
}

.faq-answer {
    display: none;
    padding: 0 30px 20px;
    background: white;
}

.faq-item.active .faq-answer {
    display: block;
}

@media (max-width: 768px) {
    .contact-form-wrapper {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .form-row {
        flex-direction: column;
        gap: 0;
    }

    .contact-info-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>

<script>
// FAQ Accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
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
});
</script>

<?php get_footer(); ?>