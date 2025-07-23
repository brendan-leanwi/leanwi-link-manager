<?php
namespace LEANWI_Link_Manager;
/*
Plugin Name:  LEANWI Link Manager
GitHub URI:   https://github.com/brendan-leanwi/leanwi-link-manager
Update URI:   https://github.com/brendan-leanwi/leanwi-link-manager
Description:  Functionality for managing and displaying links to resources via a table for LEANWI Divi WordPress websites
Version:      0.0.1
Author:       Brendan Tuckey
Author URI:   https://github.com/brendan-leanwi
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  leanwi-tutorial
Domain Path:  /languages
Tested up to: 6.8.1
*/

// plugin functionality php files
require_once plugin_dir_path(__FILE__) . 'php/plugin/menu-functions.php';  
require_once plugin_dir_path(__FILE__) . 'php/plugin/schema.php'; 
require_once plugin_dir_path(__FILE__) . 'php/plugin/plugin-updater.php';

// Hook to run when the plugin is activated
register_activation_hook(__FILE__, __NAMESPACE__ . '\\leanwi_create_tables');

// Hook to run when the plugin is uninstalled
register_uninstall_hook(__FILE__, __NAMESPACE__ . '\\leanwi_drop_tables');

// Version-based update check
function leanwi_update_check() {
    $current_version = get_option('leanwi_link_manager_version', '0.0.0'); // Default to an old version if not set
    $new_version = '0.0.1'; // Update this with the new plugin version

    if (version_compare($current_version, $new_version, '<')) {
        // Run the table creation logic
        leanwi_create_tables();

        // Update the version in the database
        update_option('leanwi_link_manager_version', $new_version);
    }
}
add_action('admin_init', __NAMESPACE__ . '\\leanwi_update_check');

// Register the JavaScript files
function leanwi_enqueue_scripts() {
    
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\leanwi_enqueue_scripts');


function enqueue_custom_styles() {
    
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_custom_styles');
