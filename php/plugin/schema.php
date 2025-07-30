<?php
namespace LEANWI_Link_Manager;

// Function to create the necessary tables on plugin activation
function leanwi_lm_create_tables() {
    // Load WordPress environment to access $wpdb
    require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $engine = "ENGINE=InnoDB";

    $sql1 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_program_area (
        area_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        display_order INT NOT NULL
    ) $engine $charset_collate;";


    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_formats (
        format_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        icon_url VARCHAR(255) NOT NULL,
        use_icon TINYINT(1) DEFAULT 0,
        display_order INT NOT NULL
    ) $engine $charset_collate;";

    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_links (
        link_id INT AUTO_INCREMENT PRIMARY KEY,
        area_id INT NOT NULL,
        link_url VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        description TEXT,
        format_id INT,
        is_featured_link TINYINT(1) DEFAULT 0,
        FOREIGN KEY (area_id) REFERENCES {$wpdb->prefix}leanwi_lm_program_area(area_id) ON DELETE CASCADE,
        FOREIGN KEY (format_id) REFERENCES {$wpdb->prefix}leanwi_lm_formats(format_id) ON DELETE SET NULL
    ) $engine $charset_collate;";

    $sql4 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_tags (
        tag_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        display_order INT NOT NULL
    ) $engine $charset_collate;";

    $sql5 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_linktags (
        link_id INT NOT NULL,
        tag_id INT NOT NULL,
        PRIMARY KEY (link_id, tag_id),
        FOREIGN KEY (link_id) REFERENCES {$wpdb->prefix}leanwi_lm_links(link_id) ON DELETE CASCADE,
        FOREIGN KEY (tag_id) REFERENCES {$wpdb->prefix}leanwi_lm_tags(tag_id) ON DELETE CASCADE
    ) $engine $charset_collate;";

    $sql6 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_related_links (
        relationship_id INT NOT NULL,
        link_id INT NOT NULL,
        PRIMARY KEY (relationship_id, link_id),
        FOREIGN KEY (link_id) REFERENCES {$wpdb->prefix}leanwi_lm_links(link_id) ON DELETE CASCADE
    ) $engine $charset_collate;";
    // Execute the SQL queries
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    //*************************************************************************************** */
    // Apparently could do the following but the way I am doing it is safer
    //dbDelta($sql1 . $sql2 . $sql3 . $sql4 . $sql5);
    //*************************************************************************************** */
    try {
        dbDelta($sql1);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error1: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }    

    try {
        dbDelta($sql2);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error2: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }    

    try {
        dbDelta($sql3);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error3: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }    

    try {
        dbDelta($sql4);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error4: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    try {
        dbDelta($sql5);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error5: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    try {
        dbDelta($sql6);
        // Debug logging to track SQL execution
        if ($wpdb->last_error) {
            error_log('DB Error6: ' . $wpdb->last_error); // Logs the error to wp-content/debug.log
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}


// Function to drop the tables on plugin uninstall
function leanwi_lm_drop_tables() {
    global $wpdb;

    // SQL to drop the tables
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_related_links");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_linktags");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_links");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_formats");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_program_area");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_tags");
}
