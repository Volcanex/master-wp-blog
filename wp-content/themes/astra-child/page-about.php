<?php
/**
 * Template Name: Business About Page
 *
 * This template is designed for business about us pages
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

get_header(); ?>

<div class="ast-container">
    <div class="business-about-page">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <!-- Hero Section -->
                <header class="business-about-hero">
                    <div class="business-container">
                        <div class="about-hero-content">
                            <h1 class="business-heading"><?php the_title(); ?></h1>
                            <?php if (get_the_content()) : ?>
                                <div class="about-hero-intro">
                                    <?php the_content(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Company Story Section -->
        <section class="company-story business-py-8">
            <div class="business-container">
                <div class="business-flex">
                    <div class="story-content">
                        <h2>Our Story</h2>
                        <p>Founded with a vision to help businesses thrive in the digital age, our company has been at the forefront of innovative solutions for over a decade. We believe that every business, regardless of size, deserves access to professional-grade services that drive growth and success.</p>

                        <p>Our team combines technical expertise with creative thinking to deliver solutions that not only meet today's needs but also prepare our clients for tomorrow's opportunities. We've helped hundreds of businesses transform their operations and achieve their goals.</p>

                        <div class="company-stats">
                            <div class="stat-item">
                                <span class="stat-number">500+</span>
                                <span class="stat-label">Projects Completed</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">250+</span>
                                <span class="stat-label">Happy Clients</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">10+</span>
                                <span class="stat-label">Years Experience</span>
                            </div>
                        </div>
                    </div>

                    <div class="story-image">
                        <div class="placeholder-image">
                            <i class="fas fa-building" style="font-size: 120px; color: var(--business-primary); opacity: 0.3;"></i>
                            <p style="margin-top: 20px; color: #666;">Company Image Placeholder</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission, Vision, Values Section -->
        <section class="mission-vision-values" style="background-color: var(--business-light);">
            <div class="business-container business-py-8">
                <div class="mvv-grid">
                    <div class="mvv-item">
                        <div class="mvv-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Our Mission</h3>
                        <p>To empower businesses with innovative solutions that drive growth, efficiency, and long-term success in an ever-evolving digital landscape.</p>
                    </div>

                    <div class="mvv-item">
                        <div class="mvv-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Our Vision</h3>
                        <p>To be the leading partner for businesses seeking to transform and thrive in the digital age, making professional services accessible to all.</p>
                    </div>

                    <div class="mvv-item">
                        <div class="mvv-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Our Values</h3>
                        <ul>
                            <li>Integrity in all our dealings</li>
                            <li>Innovation in our solutions</li>
                            <li>Excellence in our delivery</li>
                            <li>Partnership with our clients</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team-section business-py-8">
            <div class="business-container">
                <h2 class="business-text-center business-mb-4">Meet Our Team</h2>
                <p class="business-text-center" style="margin-bottom: 60px;">The passionate professionals behind our success</p>

                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-photo">
                            <i class="fas fa-user" style="font-size: 60px; color: var(--business-primary);"></i>
                        </div>
                        <h4>John Smith</h4>
                        <p class="member-title">Founder & CEO</p>
                        <p class="member-bio">With over 15 years of industry experience, John leads our team with vision and expertise.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="member-photo">
                            <i class="fas fa-user" style="font-size: 60px; color: var(--business-primary);"></i>
                        </div>
                        <h4>Sarah Johnson</h4>
                        <p class="member-title">Head of Operations</p>
                        <p class="member-bio">Sarah ensures our projects run smoothly and our clients receive exceptional service.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="member-photo">
                            <i class="fas fa-user" style="font-size: 60px; color: var(--business-primary);"></i>
                        </div>
                        <h4>Mike Chen</h4>
                        <p class="member-title">Lead Developer</p>
                        <p class="member-bio">Mike brings technical innovation and creative problem-solving to every project.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why-choose-us" style="background-color: var(--business-light);">
            <div class="business-container business-py-8">
                <h2 class="business-text-center business-mb-4">Why Choose Us?</h2>

                <div class="reasons-grid">
                    <div class="reason-item">
                        <div class="reason-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4>Proven Expertise</h4>
                        <p>Years of experience delivering successful projects across various industries.</p>
                    </div>

                    <div class="reason-item">
                        <div class="reason-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Client-Focused</h4>
                        <p>Your success is our priority. We work closely with you to achieve your goals.</p>
                    </div>

                    <div class="reason-item">
                        <div class="reason-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h4>Innovation</h4>
                        <p>We stay ahead of trends to bring you cutting-edge solutions.</p>
                    </div>

                    <div class="reason-item">
                        <div class="reason-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p>Ongoing support to ensure your continued success and peace of mind.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="business-cta">
            <div class="business-container business-text-center">
                <h2>Ready to Work with Us?</h2>
                <p>Let's discuss how we can help your business reach its full potential.</p>
                <a href="/contact" class="ast-button business-cta-button">Get Started Today</a>
            </div>
        </section>

    </div>
</div>

<style>
/* About Page Specific Styles */
.business-about-hero {
    background: linear-gradient(135deg, var(--business-primary), var(--business-secondary));
    color: white;
    padding: 100px 0;
    text-align: center;
}

.about-hero-intro {
    font-size: 1.2em;
    margin-top: 30px;
    opacity: 0.9;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.story-content {
    flex: 1;
    padding-right: 40px;
}

.story-image {
    flex: 1;
    text-align: center;
}

.placeholder-image {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 60px 20px;
    margin: 20px 0;
}

.company-stats {
    display: flex;
    gap: 30px;
    margin-top: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2.5em;
    font-weight: bold;
    color: var(--business-primary);
}

.stat-label {
    color: #666;
    font-size: 0.9em;
}

.mvv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.mvv-item {
    text-align: center;
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.mvv-icon {
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

.mvv-item ul {
    text-align: left;
    padding-left: 20px;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.team-member {
    text-align: center;
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    transition: transform 0.3s ease;
}

.team-member:hover {
    transform: translateY(-5px);
}

.member-photo {
    width: 120px;
    height: 120px;
    background: #f8f9fa;
    border-radius: 50%;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid var(--business-primary);
}

.member-title {
    color: var(--business-primary);
    font-weight: 600;
    margin: 10px 0;
}

.member-bio {
    color: #666;
    line-height: 1.6;
    margin: 15px 0;
}

.member-social {
    margin-top: 20px;
}

.member-social a {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: var(--business-primary);
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    margin: 0 5px;
    transition: background-color 0.3s ease;
}

.member-social a:hover {
    background: var(--business-secondary);
}

.reasons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.reason-item {
    text-align: center;
    background: white;
    padding: 40px 20px;
    border-radius: 12px;
}

.reason-icon {
    background: var(--business-primary);
    color: white;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 20px;
}

@media (max-width: 768px) {
    .story-content {
        padding-right: 0;
        margin-bottom: 40px;
    }

    .business-flex {
        flex-direction: column;
    }

    .company-stats {
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
    }
}
</style>

<?php get_footer(); ?>