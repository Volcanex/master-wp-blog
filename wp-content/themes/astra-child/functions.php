<?php
/**
 * Astra Business Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Business Child
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ASTRA_BUSINESS_CHILD_THEME_VERSION', '1.0.0' );

/**
 * Enqueue parent and child theme styles
 */
function astra_business_child_enqueue_styles() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );

    // Enqueue child theme stylesheet
    wp_enqueue_style(
        'astra-business-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'astra-parent-style' ),
        ASTRA_BUSINESS_CHILD_THEME_VERSION
    );

    // Enqueue custom business scripts if needed
    wp_enqueue_script(
        'astra-business-child-script',
        get_stylesheet_directory_uri() . '/assets/js/business-custom.js',
        array( 'jquery' ),
        ASTRA_BUSINESS_CHILD_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'astra_business_child_enqueue_styles' );

/**
 * Business Theme Customizations
 */

/**
 * Add business-specific theme support
 */
function astra_business_child_theme_support() {
    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for custom header
    add_theme_support( 'custom-header', array(
        'default-image'      => '',
        'default-text-color' => '000',
        'width'              => 1920,
        'height'             => 600,
        'flex-height'        => true,
        'flex-width'         => true,
    ) );

    // Add support for post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Add support for HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ) );
}
add_action( 'after_setup_theme', 'astra_business_child_theme_support' );

/**
 * Register business widget areas
 */
function astra_business_child_widgets_init() {
    // Business Services Widget Area
    register_sidebar( array(
        'name'          => __( 'Business Services', 'astra-business-child' ),
        'id'            => 'business-services',
        'description'   => __( 'Add widgets here to appear in the services section.', 'astra-business-child' ),
        'before_widget' => '<section id="%1$s" class="widget business-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title business-widget-title">',
        'after_title'   => '</h3>',
    ) );

    // Business Contact Info Widget Area
    register_sidebar( array(
        'name'          => __( 'Business Contact Info', 'astra-business-child' ),
        'id'            => 'business-contact',
        'description'   => __( 'Add contact information widgets here.', 'astra-business-child' ),
        'before_widget' => '<div id="%1$s" class="widget contact-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="contact-widget-title">',
        'after_title'   => '</h4>',
    ) );

    // Business CTA Widget Area
    register_sidebar( array(
        'name'          => __( 'Business Call to Action', 'astra-business-child' ),
        'id'            => 'business-cta',
        'description'   => __( 'Add call-to-action widgets here.', 'astra-business-child' ),
        'before_widget' => '<div id="%1$s" class="widget cta-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="cta-widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'astra_business_child_widgets_init' );

/**
 * Add custom business-specific functions
 */

/**
 * Custom excerpt length for business content
 */
function astra_business_excerpt_length( $length ) {
    if ( is_home() || is_archive() ) {
        return 25; // Shorter excerpts for business listings
    }
    return $length;
}
add_filter( 'excerpt_length', 'astra_business_excerpt_length' );

/**
 * Custom excerpt more text
 */
function astra_business_excerpt_more( $more ) {
    return '... <a href="' . get_permalink() . '" class="business-read-more">' . __( 'Read More', 'astra-business-child' ) . '</a>';
}
add_filter( 'excerpt_more', 'astra_business_excerpt_more' );

/**
 * Add business schema markup
 */
function astra_business_schema_markup() {
    if ( is_home() || is_front_page() ) {
        $business_name = get_bloginfo( 'name' );
        $business_description = get_bloginfo( 'description' );
        $business_url = home_url();

        echo '<script type="application/ld+json">';
        echo json_encode( array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $business_name,
            'description' => $business_description,
            'url' => $business_url,
            'logo' => wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'contactType' => 'customer service'
            )
        ) );
        echo '</script>';
    }
}
add_action( 'wp_head', 'astra_business_schema_markup' );

/**
 * Customize login page for business branding
 */
function astra_business_login_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo esc_url( $logo_url ); ?>);
                height: 80px;
                width: 300px;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
        <?php
    }
}
add_action( 'login_enqueue_scripts', 'astra_business_login_logo' );

/**
 * Business-specific customizer settings
 */
function astra_business_customizer_settings( $wp_customize ) {
    // Business Info Section
    $wp_customize->add_section( 'business_info', array(
        'title'    => __( 'Business Information', 'astra-business-child' ),
        'priority' => 30,
    ) );

    // Business Phone
    $wp_customize->add_setting( 'business_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'business_phone', array(
        'label'    => __( 'Business Phone', 'astra-business-child' ),
        'section'  => 'business_info',
        'type'     => 'text',
    ) );

    // Business Email
    $wp_customize->add_setting( 'business_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ) );

    $wp_customize->add_control( 'business_email', array(
        'label'    => __( 'Business Email', 'astra-business-child' ),
        'section'  => 'business_info',
        'type'     => 'email',
    ) );

    // Business Address
    $wp_customize->add_setting( 'business_address', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'business_address', array(
        'label'    => __( 'Business Address', 'astra-business-child' ),
        'section'  => 'business_info',
        'type'     => 'textarea',
    ) );

    // Business Hours
    $wp_customize->add_setting( 'business_hours', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'business_hours', array(
        'label'    => __( 'Business Hours', 'astra-business-child' ),
        'section'  => 'business_info',
        'type'     => 'textarea',
    ) );
}
add_action( 'customize_register', 'astra_business_customizer_settings' );

/**
 * Helper functions for business information
 */
function get_business_phone() {
    return get_theme_mod( 'business_phone', '' );
}

function get_business_email() {
    return get_theme_mod( 'business_email', '' );
}

function get_business_address() {
    return get_theme_mod( 'business_address', '' );
}

function get_business_hours() {
    return get_theme_mod( 'business_hours', '' );
}

/**
 * Add business contact shortcodes
 */
function business_contact_info_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'show' => 'all', // all, phone, email, address, hours
    ), $atts );

    $output = '<div class="business-contact-info">';

    if ( $atts['show'] === 'all' || $atts['show'] === 'phone' ) {
        $phone = get_business_phone();
        if ( $phone ) {
            $output .= '<div class="business-phone"><i class="fas fa-phone"></i> <a href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></div>';
        }
    }

    if ( $atts['show'] === 'all' || $atts['show'] === 'email' ) {
        $email = get_business_email();
        if ( $email ) {
            $output .= '<div class="business-email"><i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></div>';
        }
    }

    if ( $atts['show'] === 'all' || $atts['show'] === 'address' ) {
        $address = get_business_address();
        if ( $address ) {
            $output .= '<div class="business-address"><i class="fas fa-map-marker-alt"></i> ' . wp_kses_post( $address ) . '</div>';
        }
    }

    if ( $atts['show'] === 'all' || $atts['show'] === 'hours' ) {
        $hours = get_business_hours();
        if ( $hours ) {
            $output .= '<div class="business-hours"><i class="fas fa-clock"></i> ' . wp_kses_post( $hours ) . '</div>';
        }
    }

    $output .= '</div>';

    return $output;
}
add_shortcode( 'business_contact', 'business_contact_info_shortcode' );

/**
 * Hostinger-specific optimizations
 */

/**
 * Disable WordPress emoji scripts for better performance
 */
function astra_business_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'astra_business_disable_emojis' );

/**
 * Remove WordPress version from RSS feeds and head
 */
function astra_business_remove_version() {
    return '';
}
add_filter( 'the_generator', 'astra_business_remove_version' );

/**
 * Business-specific image sizes
 */
function astra_business_image_sizes() {
    add_image_size( 'business-hero', 1920, 800, true );
    add_image_size( 'business-service', 400, 300, true );
    add_image_size( 'business-team', 300, 300, true );
    add_image_size( 'business-portfolio', 600, 400, true );
}
add_action( 'after_setup_theme', 'astra_business_image_sizes' );

/**
 * Add async/defer attributes to scripts for performance
 */
function astra_business_script_attributes( $tag, $handle ) {
    $async_scripts = array( 'business-analytics', 'business-tracking' );
    $defer_scripts = array( 'astra-business-child-script' );

    if ( in_array( $handle, $async_scripts ) ) {
        return str_replace( ' src', ' async src', $tag );
    }

    if ( in_array( $handle, $defer_scripts ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'astra_business_script_attributes', 10, 2 );