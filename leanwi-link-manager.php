<?php
namespace LEANWI_Link_Manager;
/*
Plugin Name:  LEANWI Link Manager
GitHub URI:   https://github.com/brendan-leanwi/leanwi-link-manager
Update URI:   https://github.com/brendan-leanwi/leanwi-link-manager
Description:  Functionality for managing and displaying links to resources via a table for LEANWI Divi WordPress websites
Version:      0.1.3
Author:       Brendan Tuckey
Author URI:   https://github.com/brendan-leanwi
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  leanwi-tutorial
Domain Path:  /languages
Tested up to: 6.9.9
*/

// plugin functionality php files
require_once plugin_dir_path(__FILE__) . 'php/plugin/menu-functions.php';  
require_once plugin_dir_path(__FILE__) . 'php/plugin/schema.php'; 
require_once plugin_dir_path(__FILE__) . 'php/plugin/plugin-updater.php';

// links/table functionality files
require_once plugin_dir_path(__FILE__) . 'php/frontend/links-list-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'php/frontend/ajax-list-handlers.php';
require_once plugin_dir_path(__FILE__) . 'php/frontend/links-simple-list-shortcode.php';

// feed functionality files
require_once plugin_dir_path(__FILE__) . 'php/frontend/links-feed-shortcode.php';

// featured functionality files
require_once plugin_dir_path(__FILE__) . 'php/frontend/links-featured-shortcode.php';


// Hook to run when the plugin is activated
register_activation_hook(__FILE__, __NAMESPACE__ . '\\leanwi_lm_create_tables');

// Hook to run when the plugin is uninstalled
register_uninstall_hook(__FILE__, __NAMESPACE__ . '\\leanwi_lm_drop_tables');

// Version-based update check
function leanwi_lm_update_check() {
    $current_version = get_option('leanwi_link_manager_version', '0.0.5'); // Default to an old version if not set
    $new_version = '0.1.3'; // Update this with the new plugin version

    if (version_compare($current_version, $new_version, '<')) {
        // Run the table creation logic
        leanwi_lm_create_tables();

        // Update the version in the database
        update_option('leanwi_link_manager_version', $new_version);
    }
}
add_action('admin_init', __NAMESPACE__ . '\\leanwi_lm_update_check');

function leanwi_lm_enqueue_scripts() {
    // register list scripts
    wp_enqueue_script(
        'leanwi-link-manager-ajax',
        plugin_dir_url(__FILE__) . 'js/leanwi-link-manager.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'leanwi-link-related-resources-js',
        plugin_dir_url(__FILE__) . 'js/leanwi-link-related-resources.js',
        array('jquery'),
        filemtime(plugin_dir_path(__FILE__) . 'js/leanwi-link-related-resources.js'), // Version based on file modification time
        true
    );

    wp_localize_script('leanwi-link-manager-ajax', 'LEANWI_LINK_MANAGER_AJAX', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);

    wp_enqueue_style(
        'leanwi-link-manager-style',
        plugin_dir_url(__FILE__) . 'css/leanwi-link-manager.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'css/leanwi-link-manager.css')
    );
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\leanwi_lm_enqueue_scripts');

function leanwi_lm_hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2)),
    ];
}

function leanwi_lm_output_custom_color_variables() {
    $accent  = get_option('leanwi_lm_accent_color', '#0f62fe');
    $surface = get_option('leanwi_lm_surface_color', '#f8fafc');
    $text    = get_option('leanwi_lm_text_color', '#102a43');

    $accent  = sanitize_hex_color($accent) ?: '#0f62fe';
    $surface = sanitize_hex_color($surface) ?: '#f8fafc';
    $text    = sanitize_hex_color($text) ?: '#102a43';

    $accent_rgb = leanwi_lm_hex_to_rgb($accent);

    $css = "
        :root {
            --leanwi-lm-accent: {$accent};
            --leanwi-lm-surface: {$surface};
            --leanwi-lm-text: {$text};
            --leanwi-lm-focus-ring: rgba({$accent_rgb['r']}, {$accent_rgb['g']}, {$accent_rgb['b']}, 0.15);
        }
    ";

    wp_add_inline_style('leanwi-link-manager-style', $css);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\leanwi_lm_output_custom_color_variables', 20);


function enqueue_custom_styles() {
    
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_custom_styles');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('dashicons');
});
