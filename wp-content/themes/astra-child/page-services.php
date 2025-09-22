<?php
/**
 * Template Name: Business Services Page
 *
 * This template is designed for showcasing business services
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

get_header(); ?>

<div class="ast-container">
    <div class="business-services-page">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <header class="business-page-header">
                    <div class="business-container">
                        <h1 class="business-heading"><?php the_title(); ?></h1>
                        <?php if (get_the_content()) : ?>
                            <div class="business-intro">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Services Grid Section -->
        <section class="business-services business-py-8">
            <div class="business-container">

                <?php
                // Check if services are defined in customizer or create default services
                $default_services = array(
                    array(
                        'title' => 'Web Design',
                        'description' => 'Professional website design tailored to your business needs.',
                        'icon' => 'fas fa-laptop-code'
                    ),
                    array(
                        'title' => 'Digital Marketing',
                        'description' => 'Comprehensive digital marketing strategies to grow your online presence.',
                        'icon' => 'fas fa-chart-line'
                    ),
                    array(
                        'title' => 'Business Consulting',
                        'description' => 'Expert advice to help optimize your business operations and strategy.',
                        'icon' => 'fas fa-handshake'
                    ),
                    array(
                        'title' => 'SEO Services',
                        'description' => 'Search engine optimization to improve your website\'s visibility.',
                        'icon' => 'fas fa-search'
                    ),
                    array(
                        'title' => 'E-commerce Solutions',
                        'description' => 'Complete online store setup and management for your products.',
                        'icon' => 'fas fa-shopping-cart'
                    ),
                    array(
                        'title' => 'Technical Support',
                        'description' => '24/7 technical support to keep your business running smoothly.',
                        'icon' => 'fas fa-tools'
                    )
                );
                ?>

                <div class="business-grid">
                    <?php foreach ($default_services as $service) : ?>
                        <div class="business-card service-card">
                            <div class="service-icon">
                                <i class="<?php echo esc_attr($service['icon']); ?>"></i>
                            </div>
                            <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                            <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                            <a href="#contact" class="service-cta">Learn More</a>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="business-cta">
            <div class="business-container business-text-center">
                <h2>Ready to Get Started?</h2>
                <p>Contact us today to discuss how we can help your business grow and succeed.</p>
                <a href="#contact" class="ast-button business-cta-button">Get In Touch</a>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="business-faq business-py-8">
            <div class="business-container">
                <h2 class="business-text-center business-mb-4">Frequently Asked Questions</h2>

                <div class="faq-grid">
                    <div class="faq-item">
                        <h4>How long does a typical project take?</h4>
                        <p>Project timelines vary depending on scope and complexity. Most projects are completed within 2-6 weeks.</p>
                    </div>

                    <div class="faq-item">
                        <h4>Do you provide ongoing support?</h4>
                        <p>Yes, we offer comprehensive support packages to ensure your business continues to thrive online.</p>
                    </div>

                    <div class="faq-item">
                        <h4>What's included in your services?</h4>
                        <p>Each service package is customized to your needs and includes consultation, implementation, and follow-up support.</p>
                    </div>

                    <div class="faq-item">
                        <h4>Can you work with our existing systems?</h4>
                        <p>Absolutely! We specialize in integrating with existing business systems and processes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="business-contact business-py-8">
            <div class="business-container">
                <div class="business-flex">
                    <div class="contact-info">
                        <h3>Get In Touch</h3>
                        <p>Ready to discuss your project? We'd love to hear from you.</p>

                        <?php echo do_shortcode('[business_contact]'); ?>

                    </div>

                    <div class="contact-form">
                        <h3>Send Us a Message</h3>

                        <!-- Basic contact form - can be replaced with Contact Form 7 or other form plugins -->
                        <form class="business-form" method="post" action="">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Your Name" required>
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" placeholder="Your Email" required>
                            </div>

                            <div class="form-group">
                                <input type="text" name="service" placeholder="Service Interested In">
                            </div>

                            <div class="form-group">
                                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="ast-button">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
/* Services Page Specific Styles */
.business-services-page .business-page-header {
    background: linear-gradient(135deg, var(--business-primary), var(--business-secondary));
    color: white;
    padding: 80px 0;
    text-align: center;
}

.business-services-page .business-intro {
    font-size: 1.1em;
    margin-top: 20px;
    opacity: 0.9;
}

.service-card {
    text-align: center;
    transition: transform 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
}

.service-title {
    color: var(--business-dark);
    margin: 20px 0 15px;
}

.service-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.service-cta {
    color: var(--business-primary);
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.service-cta:hover {
    border-color: var(--business-primary);
}

.business-faq {
    background-color: var(--business-light);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.faq-item {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.faq-item h4 {
    color: var(--business-dark);
    margin-bottom: 15px;
}

.contact-info,
.contact-form {
    flex: 1;
    margin: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.business-form input,
.business-form textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
}

.business-form input:focus,
.business-form textarea:focus {
    border-color: var(--business-primary);
    outline: none;
}

@media (max-width: 768px) {
    .business-flex {
        flex-direction: column;
    }

    .contact-info,
    .contact-form {
        margin: 10px 0;
    }
}
</style>

<?php get_footer(); ?>