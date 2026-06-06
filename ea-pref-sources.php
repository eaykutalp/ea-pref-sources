<?php
/**
 * Plugin Name: Google Preferred Sources Helper
 * Plugin URI:  https://emreaykutalp.com
 * Description: Blog yazılarının ilk paragrafının hemen üstünde, ziyaretçileri sitenizi Google'da "Tercih Edilen Kaynak" olarak eklemeye davet eden şık ve responsive bir kart görüntüler.
 * Version:     1.0.0
 * Author:      Emre AYKUTALP
 * Author URI:  https://emreaykutalp.com
 * Text Domain: ea-pref-sources
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue styles
function eaps_enqueue_styles() {
    if ( is_single() ) {
        wp_enqueue_style(
            'eaps-styles',
            plugins_url( 'assets/style.css', __FILE__ ),
            array(),
            '1.0.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'eaps_enqueue_styles' );

// Register settings
function eaps_register_settings() {
    register_setting( 'eaps_settings_group', 'eaps_domain', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'emreaykutalp.com',
    ) );
    register_setting( 'eaps_settings_group', 'eaps_title', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Google\'da Tercih Edilen Kaynaklarına Ekle',
    ) );
    register_setting( 'eaps_settings_group', 'eaps_description', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Google aramalarında ve yapay zekâ özetlerinde bu sitenin yazılarını öncelikli görün.',
    ) );
    register_setting( 'eaps_settings_group', 'eaps_button_text', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Kaynağı Ekle',
    ) );
}
add_action( 'admin_init', 'eaps_register_settings' );

// Add settings page to Settings menu
function eaps_add_settings_page() {
    add_options_page(
        'Google Preferred Source',
        'Google Preferred Source',
        'manage_options',
        'ea-pref-sources',
        'eaps_render_settings_page'
    );
}
add_action( 'admin_menu', 'eaps_add_settings_page' );

// Render settings page
function eaps_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Google Preferred Source Ayarları', 'ea-pref-sources' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'eaps_settings_group' );
            do_settings_sections( 'ea-pref-sources' );
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Hedef Alan Adı (Domain)', 'ea-pref-sources' ); ?></th>
                    <td>
                        <input type="text" name="eaps_domain" value="<?php echo esc_attr( get_option( 'eaps_domain', 'emreaykutalp.com' ) ); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e( 'Kullanıcıların Google tercih edilen kaynaklarına eklenecek alan adı (Örn: emreaykutalp.com).', 'ea-pref-sources' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Kart Başlığı', 'ea-pref-sources' ); ?></th>
                    <td>
                        <input type="text" name="eaps_title" value="<?php echo esc_attr( get_option( 'eaps_title', 'Google\'da Tercih Edilen Kaynaklarına Ekle' ) ); ?>" class="large-text" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Kart Açıklaması', 'ea-pref-sources' ); ?></th>
                    <td>
                        <textarea name="eaps_description" rows="3" class="large-text"><?php echo esc_textarea( get_option( 'eaps_description', 'Google aramalarında ve yapay zekâ özetlerinde bu sitenin yazılarını öncelikli görün.' ) ); ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Buton Metni', 'ea-pref-sources' ); ?></th>
                    <td>
                        <input type="text" name="eaps_button_text" value="<?php echo esc_attr( get_option( 'eaps_button_text', 'Kaynağı Ekle' ) ); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Generate banner HTML
function eaps_get_banner_html() {
    $domain      = get_option( 'eaps_domain', 'emreaykutalp.com' );
    $title       = get_option( 'eaps_title', 'Google\'da Tercih Edilen Kaynaklarına Ekle' );
    $description = get_option( 'eaps_description', 'Google aramalarında ve yapay zekâ özetlerinde bu sitenin yazılarını öncelikli görün.' );
    $button_text = get_option( 'eaps_button_text', 'Kaynağı Ekle' );

    $google_url = 'https://www.google.com/preferences/source?q=' . urlencode( $domain );

    ob_start();
    ?>
    <a href="<?php echo esc_url( $google_url ); ?>" target="_blank" rel="noopener noreferrer" class="ea-pref-card">
        <div class="ea-pref-left">
            <div class="ea-pref-icon-wrap">
                <svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335" />
                </svg>
            </div>
            <div class="ea-pref-info">
                <h4 class="ea-pref-title"><?php echo esc_html( $title ); ?></h4>
                <p class="ea-pref-desc"><?php echo esc_html( $description ); ?></p>
            </div>
        </div>
        <div class="ea-pref-button">
            <?php echo esc_html( $button_text ); ?>
            <svg class="ea-pref-arrow" viewBox="0 0 24 24" width="24" height="24">
                <path d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

// Inject banner before the first paragraph
function eaps_inject_before_first_paragraph( $content ) {
    // Only inject in single blog posts in the main query loop
    if ( is_single() && in_the_loop() && is_main_query() ) {
        $banner_html = eaps_get_banner_html();
        
        // Find the first <p> tag
        $paragraph_position = strpos( $content, '<p>' );
        
        if ( false !== $paragraph_position ) {
            // Insert banner before the first <p>
            $content = substr_replace( $content, $banner_html, $paragraph_position, 0 );
        } else {
            // Fallback: prepend if no <p> tag is found
            $content = $banner_html . $content;
        }
    }
    return $content;
}
add_filter( 'the_content', 'eaps_inject_before_first_paragraph' );
