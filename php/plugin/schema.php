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
        revise_date DATETIME NULL,
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

    $sql7 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_audience (
        audience_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        display_order INT NOT NULL
    ) $engine $charset_collate;";

    $sql8 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_linkaudience (
        link_id INT NOT NULL,
        audience_id INT NOT NULL,
        PRIMARY KEY (link_id, audience_id),
        FOREIGN KEY (link_id) REFERENCES {$wpdb->prefix}leanwi_lm_links(link_id) ON DELETE CASCADE,
        FOREIGN KEY (audience_id) REFERENCES {$wpdb->prefix}leanwi_lm_audience(audience_id) ON DELETE CASCADE
    ) $engine $charset_collate;";

    $sql9 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}leanwi_lm_linkprogram_area (
        link_id INT NOT NULL,
        area_id INT NOT NULL,
        PRIMARY KEY (link_id, area_id),
        FOREIGN KEY (link_id) REFERENCES {$wpdb->prefix}leanwi_lm_links(link_id) ON DELETE CASCADE,
        FOREIGN KEY (area_id) REFERENCES {$wpdb->prefix}leanwi_lm_program_area(area_id) ON DELETE CASCADE
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

    try {
        dbDelta($sql7);
        if ($wpdb->last_error) {
            error_log('DB Error7: ' . $wpdb->last_error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    try {
        dbDelta($sql8);
        if ($wpdb->last_error) {
            error_log('DB Error8: ' . $wpdb->last_error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    try {
        dbDelta($sql9);
        if ($wpdb->last_error) {
            error_log('DB Error9: ' . $wpdb->last_error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    /* CODE TO ADD REVISE DATE - THIS UPDATE SHOULD BE LONG GONE...
    // Define the table name
    $table_name = $wpdb->prefix . 'leanwi_lm_links';

    // Check if the 'revise_date' column exists
    $column_exists = $wpdb->get_results(
        $wpdb->prepare(
            "SHOW COLUMNS FROM $table_name LIKE %s",
            'revise_date'
        )
    );

    if (count($column_exists) === 0) {
        error_log("revise_date column does not exist in $table_name");
        // Add the revise_date 
        $result =  $wpdb->query(
            "ALTER TABLE $table_name 
             ADD COLUMN revise_date DATETIME NULL"
        );

        if ($result !== false) {
            $wpdb->query("UPDATE $table_name 
                          SET revise_date = creation_date + INTERVAL 6 MONTH 
                          WHERE revise_date IS NULL");
        }

        if ($result === false) {
            error_log("Failed to add new column to $table_name: " . $wpdb->last_error);
        }
    }*/

    // Backfill leanwi_lm_linkprogram_area from existing leanwi_lm_links.area_id.
    // This preserves each existing link's current single program area as its first many-to-many entry.
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $linkprogram_area_table = $wpdb->prefix . 'leanwi_lm_linkprogram_area';

    try {
        $wpdb->query(
            "INSERT IGNORE INTO $linkprogram_area_table (link_id, area_id)
            SELECT link_id, area_id
            FROM $links_table
            WHERE area_id IS NOT NULL
            AND area_id > 0"
        );

        if ($wpdb->last_error) {
            error_log('DB Error linkprogram_area backfill: ' . $wpdb->last_error);
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
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_linkaudience");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_linkprogram_area");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_linktags");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_links");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_formats");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_program_area");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_audience");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leanwi_lm_tags");
}
