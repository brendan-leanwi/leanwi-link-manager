<?php
namespace LEANWI_Link_Manager;
/**************************************************************************************************
 * Main Menu and Main Page
 **************************************************************************************************/

function leanwi_lm_add_admin_menu() {
    // Parent menu: "LEANWI Link Manager"
    add_menu_page(
        'LEANWI Link Manager',   // Page title (for the parent menu)
        'LEANWI Link Manager',     // Menu title (for the plugin name in the dashboard)
        'manage_options',         // Capability
        'leanwi-link-manager-main', // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_main_page',       // Callback function
        'dashicons-admin-links',     // Menu icon (optional)
        9                         // Position
    );

    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Documentation and Support',  // Page title (for the actual documentation page)
        'Documentation',              // Menu title (this will be the first submenu item)
        'manage_options',             // Capability
        'leanwi-link-manager-main',    // Menu slug (reuse 'leanwi-link-manager-main' to link it to the parent page)
        __NAMESPACE__ . '\\leanwi_main_page'            // Callback function (this will now display the Documentation page)
    );

    // Sub-menu: "Manage Links"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Manage Links',                     // Page title
        'Manage Links',                     // Menu title
        'manage_options',             // Capability
        'leanwi-lm-manage-links',  // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_manager_links_page'          // Callback function to display link management page
    );

    // Sub-menu: "Add Link"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Link',
        'Add Link',
        'manage_options',
        'leanwi-lm-add-link',
        __NAMESPACE__ . '\\leanwi_lm_add_link_page'
    );

    // Sub-menu: "Edit Link"
    add_submenu_page(
        'leanwi-link-manager-main', // Parent slug
        'Edit Link',                // Page title
        'Edit Link',                // Menu title (hidden later via CSS if desired)
        'manage_options',           // Capability
        'leanwi-lm-edit-link',      // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_edit_link_page' // Callback function
    );


    // Sub-menu: "Program Areas"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Program Areas',              // Page title
        'Program Areas',              // Menu title
        'manage_options',             // Capability
        'leanwi-lm-program-areas',    // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_program_areas_page' // Callback function
    );

    // Sub-menu: "Add Program Area"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Program Area',
        'Add Program Area',
        'manage_options',
        'leanwi-lm-add-program-area',
        __NAMESPACE__ . '\\leanwi_lm_add_program_area_page'
    );

    // Sub-menu: "Edit Program Area"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Edit Program Area',
        'Edit Program Area',
        'manage_options',
        'leanwi-lm-edit-program-area',
        __NAMESPACE__ . '\\leanwi_lm_edit_program_area_page'
    );

    // Sub-menu: "Formats"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Formats',                   // Page title
        'Formats',                   // Menu title
        'manage_options',             // Capability
        'leanwi-lm-formats',// Menu slug
        __NAMESPACE__ . '\\leanwi_lm_formats_page'        // Callback function to display Formats
    );

    // Sub-menu: "Add Format"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Format',
        'Add Format',
        'manage_options',
        'leanwi-lm-add-format',
        __NAMESPACE__ . '\\leanwi_lm_add_format_page'
    );

    // Sub-menu: "Edit Format"
    add_submenu_page(
        'leanwi-link-manager-main', // Parent slug (linked to Categories submenu)
        'Edit Format',                 // Page title
        'Edit Format',                 // Menu title
        'manage_options',             // Capability
        'leanwi-lm-edit-format',          // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_edit_format_page'      // Callback function to display the edit venue form
    );

    // Sub-menu: "Tags"
    add_submenu_page(
        'leanwi-link-manager-main',    // Parent slug
        'Tags',                       // Page title
        'Tags',                       // Menu title
        'manage_options',             // Capability
        'leanwi-lm-tags',             // Menu slug
        __NAMESPACE__ . '\\leanwi_lm_tags_page' // Callback function
    );

    // Sub-menu: "Add Tag"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Add Tag',
        'Add Tag',
        'manage_options',
        'leanwi-lm-add-tag',
        __NAMESPACE__ . '\\leanwi_lm_add_tag_page'
    );

    // Sub-menu: "Edit Tag"
    add_submenu_page(
        'leanwi-link-manager-main',
        'Edit Tag',
        'Edit Tag',
        'manage_options',
        'leanwi-lm-edit-tag',
        __NAMESPACE__ . '\\leanwi_lm_edit_tag_page'
    );

}


// Hook to create the admin menu
add_action('admin_menu', __NAMESPACE__ . '\\leanwi_lm_add_admin_menu');

// Hide the Add and Edit pages submenus from the left-hand navigation menu using CSS
function leanwi_hide_add_edit_submenus_css() {
    echo '<style>
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-link"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-link"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-program-area"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-program-area"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-format"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-format"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-add-tag"],
        #toplevel_page_leanwi-link-manager-main .wp-submenu a[href="admin.php?page=leanwi-lm-edit-tag"] {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', __NAMESPACE__ . '\\leanwi_hide_add_edit_submenus_css');


// Function to display the main page which is our documentation page
function leanwi_lm_main_page() {
    $doc_file = plugin_dir_path(dirname(dirname(__FILE__))) . 'docs/documentation.html';

    if (!file_exists($doc_file)) {
        $content = "<h2>Documentation Not Found</h2><p>Please ensure `documentation.html` exists in the `docs/` directory.</p>";
    } else {
        $content = file_get_contents($doc_file);
    }
    ?>

    <div class="wrap">
        <h1>LEANWI Link Manager Documentation</h1>
        <div id="documentation-content" style="border: 1px solid #ddd; padding: 15px; background: #fff;"></div>
    </div>

    <script>
        // Function to load the HTML file dynamically
        function loadHtmlPage(page) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>" + "docs/" + page, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var content = xhr.responseText;
                    document.getElementById("documentation-content").innerHTML = content;
                    //attachLinkEvents();  // Reattach link events after new content is loaded
                }
            };
            xhr.send();
        }

        // Load the default content (documentation.html)
        var content = <?php echo json_encode($content); ?>;
        document.getElementById("documentation-content").innerHTML = content;
    </script>

    <?php
}


/**************************************************************************************************
 * Manage Links
 **************************************************************************************************/

// Function to display the table of links
function leanwi_lm_manager_links_page() {
    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';

    // Handle deletion if delete_link is set
    if (isset($_GET['delete_link'])) {
        $link_id = intval($_GET['delete_link']);
        $wpdb->delete($links_table, ['link_id' => $link_id], ['%d']);
        echo '<div class="updated"><p>Link deleted successfully.</p></div>';
    }

    // Fetch Program Areas and Formats for dropdowns
    $areas = $wpdb->get_results("SELECT area_id, name FROM $areas_table ORDER BY name ASC", ARRAY_A);
    $formats = $wpdb->get_results("SELECT format_id, name FROM $formats_table ORDER BY name ASC", ARRAY_A);
    $tags = $wpdb->get_results("SELECT tag_id, name FROM $tags_table ORDER BY name ASC", ARRAY_A);

    // Build filters form
    echo '<div class="wrap">';
    echo '<h1>Manage Links</h1>';
    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-link') . '" class="button button-primary">Add New Link</a>';

    echo '<div style="margin-top:20px; margin-bottom:20px; padding:15px; border:1px solid #ccc; background:#f9f9f9; border-radius:5px;">';
    echo '<h2>Add Link Filters</h2>';

    echo '<form method="GET">';

    // Keep necessary GET params for WordPress routing
    echo '<input type="hidden" name="page" value="leanwi-lm-manage-links">';

    echo '<table class="form-table"><tbody>';

    // Date Range
    echo '<tr>';
    echo '<th scope="row">Date Range</th>';
    echo '<td>';
    echo 'From: <input type="date" name="start_date" value="' . esc_attr($_GET['start_date'] ?? '') . '"> ';
    echo 'To: <input type="date" name="end_date" value="' . esc_attr($_GET['end_date'] ?? '') . '">';
    echo '</td>';
    echo '</tr>';

    // Format Dropdown
    echo '<tr>';
    echo '<th scope="row">Format</th>';
    echo '<td>';
    echo '<select name="format_id">';
    echo '<option value="">-- All Formats --</option>';
    foreach ($formats as $format) {
        $selected = (isset($_GET['format_id']) && $_GET['format_id'] == $format['format_id']) ? 'selected' : '';
        echo '<option value="' . esc_attr($format['format_id']) . '" ' . $selected . '>' . esc_html($format['name']) . '</option>';
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // Program Area Dropdown
    echo '<tr>';
    echo '<th scope="row">Program Area</th>';
    echo '<td>';
    echo '<select name="area_id">';
    echo '<option value="">-- All Program Areas --</option>';
    foreach ($areas as $area) {
        $selected = (isset($_GET['area_id']) && $_GET['area_id'] == $area['area_id']) ? 'selected' : '';
        echo '<option value="' . esc_attr($area['area_id']) . '" ' . $selected . '>' . esc_html($area['name']) . '</option>';
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // Featured Links Only checkbox
    echo '<tr>';
    echo '<th scope="row">Featured Links Only</th>';
    echo '<td>';
    $featured_checked = (!empty($_GET['is_featured_link'])) ? 'checked' : '';
    echo '<label><input type="checkbox" name="is_featured_link" value="1" ' . $featured_checked . '> Show only featured links</label>';
    echo '</td>';
    echo '</tr>';

    // Keyword Search
    echo '<tr>';
    echo '<th scope="row">Keyword Search</th>';
    echo '<td>';
    echo '<input type="text" name="search" value="' . esc_attr($_GET['search'] ?? '') . '" style="width:300px;">';
    echo '</td>';
    echo '</tr>';

    // Tags Checkboxes
    echo '<tr>';
    echo '<th scope="row">Tags</th>';
    echo '<td>';
    if (!empty($tags)) {
        $selected_tags = $_GET['tags'] ?? [];
        foreach ($tags as $index => $tag) {
            if ($index % 4 == 0) echo '<div style="clear: both;"></div>';
            $checked = (is_array($selected_tags) && in_array($tag['tag_id'], $selected_tags)) ? 'checked' : '';
            echo '<label style="width: 23%; display: inline-block; margin-right: 1%;">';
            echo '<input type="checkbox" name="tags[]" value="' . esc_attr($tag['tag_id']) . '" ' . $checked . '> ' . esc_html($tag['name']);
            echo '</label>';
        }
    } else {
        echo 'No tags available.';
    }
    echo '</td>';
    echo '</tr>';

    echo '</tbody></table>';

    echo '<p><input type="submit" class="button button-primary" value="Show Filtered List"></p>';
    echo '</form>';
    echo '</div>'; // Close styled div

    // Build the query with filters
    $query = "
        SELECT l.*, a.name AS area_name, f.name AS format_name
        FROM $links_table l
        LEFT JOIN $areas_table a ON l.area_id = a.area_id
        LEFT JOIN $formats_table f ON l.format_id = f.format_id
    ";

    // Build WHERE and parameters first
    $where = [];
    $params = [];

    // Date range filter
    if (!empty($_GET['start_date'])) {
        $where[] = "l.creation_date >= %s";
        $params[] = $_GET['start_date'] . ' 00:00:00';
    }
    if (!empty($_GET['end_date'])) {
        $where[] = "l.creation_date <= %s";
        $params[] = $_GET['end_date'] . ' 23:59:59';
    }

    // Format filter
    if (!empty($_GET['format_id'])) {
        $where[] = "l.format_id = %d";
        $params[] = intval($_GET['format_id']);
    }

    // Program area filter
    if (!empty($_GET['area_id'])) {
        $where[] = "l.area_id = %d";
        $params[] = intval($_GET['area_id']);
    }

    // Featured Links filter
    if (!empty($_GET['is_featured_link'])) {
        $where[] = "l.is_featured_link = 1";
    }

    // Keyword search filter
    if (!empty($_GET['search'])) {
        $where[] = "(l.title LIKE %s OR l.description LIKE %s)";
        $search = '%' . $wpdb->esc_like($_GET['search']) . '%';
        $params[] = $search;
        $params[] = $search;
    }

    // Start base query
    $query = "
        SELECT l.*, a.name AS area_name, f.name AS format_name
        FROM $links_table l
        LEFT JOIN $areas_table a ON l.area_id = a.area_id
        LEFT JOIN $formats_table f ON l.format_id = f.format_id
    ";

    // Tags filter logic (join AFTER base query built)
    $tag_params = [];
    if (!empty($_GET['tags'])) {
        $tag_ids = array_map('intval', $_GET['tags']);
        $tag_placeholders = implode(',', array_fill(0, count($tag_ids), '%d'));

        $query .= "
            INNER JOIN $linktags_table lt ON l.link_id = lt.link_id
            AND lt.tag_id IN ($tag_placeholders)
        ";
        $tag_params = $tag_ids;
    }

    // Add WHERE clause
    if (!empty($where)) {
        $query .= " WHERE " . implode(' AND ', $where);
    }

    // Group by link_id to avoid duplicates if multiple tags match
    $query .= " GROUP BY l.link_id ORDER BY l.creation_date DESC";

    // Merge tag_params FIRST since their placeholders come first
    $final_params = array_merge($tag_params, $params);

    $links = $wpdb->get_results($wpdb->prepare($query, $final_params), ARRAY_A);

    // Display results table
    if (empty($links)) {
        echo '<p>No links found for the selected filters.</p>';
    } else {
        echo '<table class="wp-list-table widefat striped">';
        echo '<thead><tr>';
        echo '<th>Title</th>';
        echo '<th>URL</th>';
        echo '<th>Program Area</th>';
        echo '<th>Format</th>';
        echo '<th>Creation Date</th>';
        echo '<th>Featured</th>';
        echo '<th>Actions</th>';
        echo '</tr></thead><tbody>';

        foreach ($links as $link) {
            echo '<tr>';
            echo '<td>' . esc_html($link['title']) . '</td>';
            echo '<td><a href="' . esc_url($link['link_url']) . '" target="_blank">' . esc_html($link['link_url']) . '</a></td>';
            echo '<td>' . esc_html($link['area_name']) . '</td>';
            echo '<td>' . esc_html($link['format_name']) . '</td>';
            echo '<td>' . esc_html($link['creation_date']) . '</td>';
            echo '<td>' . ($link['is_featured_link'] ? 'Yes' : 'No') . '</td>';
            echo '<td style="white-space:nowrap;">';
            echo '<a href="' . admin_url('admin.php?page=leanwi-lm-edit-link&link_id=' . esc_attr($link['link_id'])) . '" title="Edit Link" style="margin-right:8px;"><span class="dashicons dashicons-edit"></span></a>';
            echo '<a href="' . admin_url('admin.php?page=leanwi-lm-manage-links&delete_link=' . esc_attr($link['link_id'])) . '" class="delete-link" title="Delete Link"><span class="dashicons dashicons-trash" style="color:red;"></span></a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
    echo '<a style="margin-top: 20px" href="' . admin_url('admin.php?page=leanwi-lm-add-link') . '" class="button button-primary">Add New Link</a>';
    echo '</div>';

    // JS confirm dialog for delete
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const deleteLinks = document.querySelectorAll('.delete-link');
            deleteLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    if (!confirm('Are you sure you want to delete this link? This action cannot be undone.')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
    <?php
}

function leanwi_lm_add_link_page() {
    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';

    // Handle form submission
    if (isset($_POST['add_link'])) {
        $area_id = intval($_POST['area_id']);
        $format_id = isset($_POST['format_id']) ? intval($_POST['format_id']) : null;

        $link_url = esc_url_raw(wp_unslash($_POST['link_url']));
        $title = sanitize_text_field(wp_unslash($_POST['title']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));
        $is_featured_link = isset($_POST['is_featured_link']) ? 1 : 0;

        // Handle creation_date input
        if (!empty($_POST['creation_date'])) {
            // User provided a date – set to midnight of that date
            $creation_date = sanitize_text_field($_POST['creation_date']) . ' 00:00:00';
        } else {
            // Use current date and time
            $creation_date = current_time('mysql'); // WordPress current time in MySQL DATETIME format
        }

        // Insert the new link
        $wpdb->insert(
            $links_table,
            [
                'area_id' => $area_id,
                'link_url' => $link_url,
                'title' => $title,
                'description' => $description,
                'format_id' => $format_id,
                'is_featured_link' => $is_featured_link,
                'creation_date' => $creation_date
            ],
            ['%d', '%s', '%s', '%s', '%d', '%d', '%s']
        );

        $link_id = $wpdb->insert_id;

        // Insert selected tags into linktags table
        if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag_id) {
                $tag_id = intval($tag_id);
                $wpdb->insert(
                    $linktags_table,
                    ['link_id' => $link_id, 'tag_id' => $tag_id],
                    ['%d', '%d']
                );
            }
        }

        echo '<div class="updated"><p>Link added successfully with tags.</p></div>';
    }

    // Fetch Program Areas
    $areas = $wpdb->get_results("SELECT area_id, name FROM $areas_table ORDER BY display_order ASC", ARRAY_A);

    // Fetch Formats
    $formats = $wpdb->get_results("SELECT format_id, name FROM $formats_table ORDER BY display_order ASC", ARRAY_A);

    // Fetch Tags
    $tags = $wpdb->get_results("SELECT tag_id, name FROM $tags_table ORDER BY display_order ASC", ARRAY_A);

    // Get today's date for default value
    $today_date = date('Y-m-d');

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Add Link</h1>';
    echo '<form method="POST">';

    // Program Area dropdown
    echo '<p>Program Area: <select name="area_id" required>';
    echo '<option value="">Select Program Area</option>';
    if ($areas) {
        foreach ($areas as $area) {
            echo '<option value="' . esc_attr($area['area_id']) . '">' . esc_html($area['name']) . '</option>';
        }
    }
    echo '</select></p>';

    // Format dropdown
    echo '<p>Format: <select name="format_id">';
    echo '<option value="">None</option>';
    if ($formats) {
        foreach ($formats as $format) {
            echo '<option value="' . esc_attr($format['format_id']) . '">' . esc_html($format['name']) . '</option>';
        }
    }
    echo '</select></p>';

    // Link URL input
    echo '<p>Link URL: <input type="url" name="link_url" required style="width:600px;"></p>';

    // Title input
    echo '<p>Title: <input type="text" name="title" required style="width:400px;"></p>';

    // Description input
    echo '<p>Description: <input type="text" name="description" style="width:600px;"></p>';

    // Creation Date input
    echo '<p>Creation Date: <input type="date" name="creation_date" value="' . esc_attr($today_date) . '"></p>';

    // Featured Link checkbox
    echo '<p><label><input type="checkbox" name="is_featured_link"> Mark as Featured Link</label></p>';

    // Tags checkboxes in a 4-column grid
    echo '<p><strong>Tags:</strong></p>';
    if ($tags) {
        echo '<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:5px; max-width:800px;">';
        foreach ($tags as $tag) {
            echo '<label><input type="checkbox" name="tags[]" value="' . esc_attr($tag['tag_id']) . '"> ' . esc_html($tag['name']) . '</label>';
        }
        echo '</div>';
    } else {
        echo '<p>No tags available.</p>';
    }

    // Submit button
    echo '<p><input type="submit" name="add_link" value="Add Link" class="button button-primary"></p>';

    echo '</form>';
    echo '</div>';
}

function leanwi_lm_edit_link_page() {
    global $wpdb;
    $links_table = $wpdb->prefix . 'leanwi_lm_links';
    $areas_table = $wpdb->prefix . 'leanwi_lm_program_area';
    $formats_table = $wpdb->prefix . 'leanwi_lm_formats';
    $tags_table = $wpdb->prefix . 'leanwi_lm_tags';
    $linktags_table = $wpdb->prefix . 'leanwi_lm_linktags';

    // Check if a link_id is provided
    if (!isset($_GET['link_id'])) {
        echo '<div class="error"><p>No link ID provided.</p></div>';
        return;
    }

    $link_id = intval($_GET['link_id']);

    // Fetch the link data
    $link = $wpdb->get_row($wpdb->prepare("SELECT * FROM $links_table WHERE link_id = %d", $link_id), ARRAY_A);
    if (!$link) {
        echo '<div class="error"><p>Link not found.</p></div>';
        return;
    }

    // Handle form submission
    if (isset($_POST['update_link'])) {
        $area_id = intval($_POST['area_id']);
        $format_id = isset($_POST['format_id']) ? intval($_POST['format_id']) : null;

        $link_url = esc_url_raw(wp_unslash($_POST['link_url']));
        $title = sanitize_text_field(wp_unslash($_POST['title']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));
        $is_featured_link = isset($_POST['is_featured_link']) ? 1 : 0;

        // Handle creation_date input
        if (!empty($_POST['creation_date'])) {
            $creation_date = sanitize_text_field($_POST['creation_date']) . ' 00:00:00';
        } else {
            $creation_date = current_time('mysql');
        }

        // Update the link record
        $wpdb->update(
            $links_table,
            [
                'area_id' => $area_id,
                'link_url' => $link_url,
                'title' => $title,
                'description' => $description,
                'format_id' => $format_id,
                'is_featured_link' => $is_featured_link,
                'creation_date' => $creation_date
            ],
            ['link_id' => $link_id],
            ['%d', '%s', '%s', '%s', '%d', '%d', '%s'],
            ['%d']
        );

        // Update tags: delete existing then insert new selections
        $wpdb->delete($linktags_table, ['link_id' => $link_id], ['%d']);

        if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag_id) {
                $tag_id = intval($tag_id);
                $wpdb->insert(
                    $linktags_table,
                    ['link_id' => $link_id, 'tag_id' => $tag_id],
                    ['%d', '%d']
                );
            }
        }

        echo '<div class="updated"><p>Link updated successfully.</p></div>';

        // Refresh data
        $link = $wpdb->get_row($wpdb->prepare("SELECT * FROM $links_table WHERE link_id = %d", $link_id), ARRAY_A);
    }

    // Fetch Program Areas
    $areas = $wpdb->get_results("SELECT area_id, name FROM $areas_table ORDER BY display_order ASC", ARRAY_A);

    // Fetch Formats
    $formats = $wpdb->get_results("SELECT format_id, name FROM $formats_table ORDER BY display_order ASC", ARRAY_A);

    // Fetch Tags
    $tags = $wpdb->get_results("SELECT tag_id, name FROM $tags_table ORDER BY display_order ASC", ARRAY_A);

    // Fetch currently assigned tags
    $current_tags = $wpdb->get_col($wpdb->prepare("SELECT tag_id FROM $linktags_table WHERE link_id = %d", $link_id));

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Edit Link</h1>';
    echo '<form method="POST">';

    // Program Area dropdown
    echo '<p>Program Area: <select name="area_id" required>';
    echo '<option value="">Select Program Area</option>';
    if ($areas) {
        foreach ($areas as $area) {
            echo '<option value="' . esc_attr($area['area_id']) . '" ' . selected($area['area_id'], $link['area_id'], false) . '>' . esc_html($area['name']) . '</option>';
        }
    }
    echo '</select></p>';

    // Format dropdown
    echo '<p>Format: <select name="format_id">';
    echo '<option value="">None</option>';
    if ($formats) {
        foreach ($formats as $format) {
            echo '<option value="' . esc_attr($format['format_id']) . '" ' . selected($format['format_id'], $link['format_id'], false) . '>' . esc_html($format['name']) . '</option>';
        }
    }
    echo '</select></p>';

    // Link URL input
    echo '<p>Link URL: <input type="url" name="link_url" value="' . esc_attr($link['link_url']) . '" required style="width:600px;"></p>';

    // Title input
    echo '<p>Title: <input type="text" name="title" value="' . esc_attr($link['title']) . '" required style="width:400px;"></p>';

    // Description input
    echo '<p>Description: <input type="text" name="description" value="' . esc_attr($link['description']) . '" style="width:600px;"></p>';

    // Creation Date input
    $creation_date = (!empty($link['creation_date'])) ? date('Y-m-d', strtotime($link['creation_date'])) : date('Y-m-d');
    echo '<p>Creation Date: <input type="date" name="creation_date" value="' . esc_attr($creation_date) . '"></p>';

    // Featured Link checkbox
    echo '<p><label><input type="checkbox" name="is_featured_link" ' . checked($link['is_featured_link'], 1, false) . '> Mark as Featured Link</label></p>';

    // Tags checkboxes in a 4-column grid
    echo '<p><strong>Tags:</strong></p>';
    if ($tags) {
        echo '<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:5px; max-width:800px;">';
        foreach ($tags as $tag) {
            $checked = in_array($tag['tag_id'], $current_tags) ? 'checked' : '';
            echo '<label><input type="checkbox" name="tags[]" value="' . esc_attr($tag['tag_id']) . '" ' . $checked . '> ' . esc_html($tag['name']) . '</label>';
        }
        echo '</div>';
    } else {
        echo '<p>No tags available.</p>';
    }

    // Submit button
    echo '<p><input type="submit" name="update_link" value="Save Changes" class="button button-primary"></p>';

    echo '</form>';
    echo '</div>';
}


/**************************************************************************************************
 * Program Areas
 **************************************************************************************************/
function leanwi_lm_program_areas_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle display order update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $area_id => $display_order) {
                $area_id = intval($area_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['area_id' => $area_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Fetch program areas
    $areas = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    echo '<div class="wrap">';
    echo '<h1>Program Areas</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-program-area') . '" class="button button-primary">Add Program Area</a>';
    echo '<p></p>';

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr>
        <th>Area ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Display Order</th>
        <th>Actions</th>
    </tr></thead>';
    echo '<tbody>';

    if ($areas) {
        foreach ($areas as $area) {
            echo '<tr>';
            echo '<td>' . esc_html($area['area_id']) . '</td>';
            echo '<td>' . esc_html($area['name']) . '</td>';
            echo '<td>' . esc_html($area['description']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($area['area_id']) . ']" value="' . esc_attr($area['display_order']) . '" style="width:60px;"></td>';
            echo '<td><a href="' . admin_url('admin.php?page=leanwi-lm-edit-program-area&area_id=' . $area['area_id']) . '" class="button">Edit</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No program areas found.</td></tr>';
    }

    echo '</tbody></table>';
    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_add_program_area_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle form submission
    if (isset($_POST['add_program_area'])) {
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        // Determine new display order
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $wpdb->insert(
            $table_name,
            ['name' => $name, 'description' => $description, 'display_order' => $new_order],
            ['%s', '%s', '%d']
        );

        echo '<div class="updated"><p>Program Area added successfully.</p></div>';
    }

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Add Program Area</h1>';
    echo '<form method="POST">';
    echo '<p>Program Area Name: <input type="text" name="name" required style="width:300px;"></p>';
    echo '<p>Program Area Description: <input type="text" name="description" style="width:600px;"></p>';
    echo '<p><input type="submit" name="add_program_area" value="Add Program Area" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_edit_program_area_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_program_area';

    // Handle form submission
    if (isset($_POST['update_program_area'])) {
        $area_id = intval($_POST['area_id']);
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        $wpdb->update(
            $table_name,
            ['name' => $name, 'description' => $description],
            ['area_id' => $area_id],
            ['%s', '%s'],
            ['%d']
        );

        echo '<div class="updated"><p>Program Area updated successfully.</p></div>';
    }

    // Fetch program area for editing
    if (isset($_GET['area_id'])) {
        $area_id = intval($_GET['area_id']);
        $area = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE area_id = %d", $area_id));

        if ($area) {
            echo '<div class="wrap">';
            echo '<h1>Edit Program Area</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="area_id" value="' . esc_attr($area->area_id) . '">';
            echo '<p>Program Area Name: <input type="text" name="name" value="' . esc_attr($area->name) . '" style="width:300px;"></p>';
            echo '<p>Program Area Description: <input type="text" name="description" value="' . esc_attr($area->description) . '" style="width:600px;"></p>';
            echo '<p><input type="submit" name="update_program_area" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="error"><p>Program Area not found.</p></div>';
        }
    } else {
        echo '<div class="error"><p>No program area ID provided.</p></div>';
    }
}


/**************************************************************************************************
 * Formats
 **************************************************************************************************/

// Function to display the list of formats
function leanwi_lm_formats_page() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Process display order update if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $format_id => $display_order) {
                $format_id = intval($format_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['format_id' => $format_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Display format list
    echo '<div class="wrap">';
    echo '<h1>Formats</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-format') . '" class="button button-primary">Add Format</a>';
    echo '<p> </p>'; // Space below the button before the format table

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">Format ID</th>';
    echo '<th scope="col">Format Name</th>';
    echo '<th scope="col">Display Order</th>';
    echo '<th scope="col">Uses Icon</th>';
    echo '<th scope="col">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Fetch formats
    $formats = fetch_formats();
    if (isset($formats['error'])) {
        echo '<tr><td colspan="5">' . esc_html($formats['error']) . '</td></tr>';
    } else {
        // Display each format in a row
        foreach ($formats['formats'] as $format) {
            echo '<tr>';
            echo '<td>' . esc_html($format['format_id']) . '</td>';
            echo '<td>' . esc_html($format['name']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($format['format_id']) . ']" value="' . esc_attr($format['display_order']) . '" style="width: 60px;"></td>';
            echo '<td>' . ($format['use_icon'] ? 'Yes' : 'No') . '</td>';
            echo '<td>';
            echo '<a href="' . esc_url(admin_url('admin.php?page=leanwi-lm-edit-format&format_id=' . esc_attr($format['format_id']))) . '" class="button">Edit</a> ';
            echo '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody>';
    echo '</table>';

    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
    
}

// Function to get formats
function fetch_formats() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Fetch formats
    $formats = $wpdb->get_results("SELECT format_id, name, display_order, use_icon FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    if (empty($formats)) {
        return ['error' => 'No formats found.'];
    } else {
        return ['formats' => $formats];
    }
}

function leanwi_lm_add_format_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Handle form submission
    if (isset($_POST['add_format'])) {
        // Find the max display_order and increment
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $name = sanitize_text_field($_POST['name']);
        $name = wp_unslash($name);

        $description = sanitize_text_field($_POST['description']);
        $description = wp_unslash($description);

        $icon_url = isset($_POST['icon_url']) ? wp_unslash($_POST['icon_url']) : '';
        $icon_url = esc_url_raw($icon_url);

        $wpdb->insert(
            $table_name,
            ['name' => $name,
             'description' => $description,
             'icon_url' => $icon_url, 
             'use_icon' => isset($_POST['use_icon']) ? 1 : 0,
             'display_order' => $new_order],
            ['%s', '%s', '%s','%d', '%d']
        );
        echo '<div class="updated"><p>Format added successfully.</p></div>';
    }

    // Display the add format form
echo '<div class="wrap">';
echo '<h1>Add Format</h1>';
echo '<form method="POST">';
echo '<p>Format Name: <input type="text" name="name" required style="width: 300px;"></p>'; 
echo '<p>Format Description: <input type="text" name="description" style="width: 600px;"></p>'; 
echo '<p>Use Icon: <input type="checkbox" name="use_icon"></p>';    
echo '<p>Icon URL: <input type="text" name="icon_url" style="width: 600px;"></p>';
echo '<p><input type="submit" name="add_format" value="Add Format" class="button button-primary"></p>';
echo '</form>';
echo '</div>';

}

// Function to handle editing of a format
function leanwi_lm_edit_format_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'leanwi_lm_formats';

    // Handle the form submission to update the format
    if (isset($_POST['update_format'])) {
        $format_id = intval($_POST['format_id']);
        
        $name = sanitize_text_field($_POST['name']);
        $name = wp_unslash($name);

        $description = sanitize_text_field($_POST['description']);
        $description = wp_unslash($description);

        $icon_url = isset($_POST['icon_url']) ? wp_unslash($_POST['icon_url']) : '';
        $icon_url = esc_url_raw($icon_url);

        // Update the category in the database
        $wpdb->update(
            $table_name,
            ['name' => $name,
             'description' => $description,
             'icon_url' => $icon_url, 
             'use_icon' => isset($_POST['use_icon']) ? 1 : 0],
            ['format_id' => $format_id],
            ['%s', '%s','%s','%d'],
            ['%d']
        );

        echo '<div class="updated"><p>Format updated successfully.</p></div>';
    }

    // Check if a format ID is provided for editing
    if (isset($_GET['format_id'])) {
        $format_id = intval($_GET['format_id']);
        $format = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE format_id = %d", $format_id));

        if ($format) {
            // Display form to edit the format
            echo '<div class="wrap">';
            echo '<h1>Edit Format</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="format_id" value="' . esc_attr($format->format_id) . '">';

            // Display the format name input
            echo '<p>Format Name: <input type="text" name="name" value="' . esc_attr($format->name) . '" class="regular-text" style="width:300px;"></p>';

            echo '<p>Format Description: <input type="text" name="description" value="' . esc_attr($format->description) . '" style="width:600px;"></p>';
            echo '<p>';
            echo '<label><input type="checkbox" name="use_icon" ' . checked($format->use_icon, 1, false) . '> Use Icon</label>';
            echo '</p>';    
            echo '<p>Icon URL: <input type="text" name="icon_url" value="' . esc_attr($format->icon_url) . '" style="width:600px;"></p>';

            // Submit button to update the format
            echo '<p><input type="submit" name="update_format" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            // Display a message if the format is not found
            echo '<div class="error"><p>Format not found.</p></div>';
        }
    } else {
        // Redirect back if no format ID is provided
        echo '<div class="error"><p>No format ID provided.</p></div>';
    }
}

/**************************************************************************************************
 * Tags
 **************************************************************************************************/
function leanwi_lm_tags_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle display order update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_display_order'])) {
        if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
            foreach ($_POST['display_order'] as $tag_id => $display_order) {
                $tag_id = intval($tag_id);
                $display_order = intval($display_order);

                $wpdb->update(
                    $table_name,
                    ['display_order' => $display_order],
                    ['tag_id' => $tag_id],
                    ['%d'],
                    ['%d']
                );
            }
            echo '<div class="updated notice"><p>Display order updated successfully.</p></div>';
        }
    }

    // Fetch tags
    $tags = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    echo '<div class="wrap">';
    echo '<h1>Tags</h1>';

    echo '<a href="' . admin_url('admin.php?page=leanwi-lm-add-tag') . '" class="button button-primary">Add Tag</a>';
    echo '<p></p>';

    echo '<form method="POST">';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr>
        <th>Tag ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Display Order</th>
        <th>Actions</th>
    </tr></thead>';
    echo '<tbody>';

    if ($tags) {
        foreach ($tags as $tag) {
            echo '<tr>';
            echo '<td>' . esc_html($tag['tag_id']) . '</td>';
            echo '<td>' . esc_html($tag['name']) . '</td>';
            echo '<td>' . esc_html($tag['description']) . '</td>';
            echo '<td><input type="number" name="display_order[' . esc_attr($tag['tag_id']) . ']" value="' . esc_attr($tag['display_order']) . '" style="width:60px;"></td>';
            echo '<td><a href="' . admin_url('admin.php?page=leanwi-lm-edit-tag&tag_id=' . $tag['tag_id']) . '" class="button">Edit</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No tags found.</td></tr>';
    }

    echo '</tbody></table>';
    echo '<p><input type="submit" name="save_display_order" value="Save Display Order" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_add_tag_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle form submission
    if (isset($_POST['add_tag'])) {
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        // Determine new display order
        $max_order = $wpdb->get_var("SELECT MAX(display_order) FROM $table_name");
        $new_order = ($max_order !== null) ? $max_order + 1 : 1;

        $wpdb->insert(
            $table_name,
            ['name' => $name, 'description' => $description, 'display_order' => $new_order],
            ['%s', '%s', '%d']
        );

        echo '<div class="updated"><p>Tag added successfully.</p></div>';
    }

    // Display form
    echo '<div class="wrap">';
    echo '<h1>Add Tag</h1>';
    echo '<form method="POST">';
    echo '<p>Tag Name: <input type="text" name="name" required style="width:300px;"></p>';
    echo '<p>Tag Description: <input type="text" name="description" style="width:600px;"></p>';
    echo '<p><input type="submit" name="add_tag" value="Add Tag" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';
}

function leanwi_lm_edit_tag_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'leanwi_lm_tags';

    // Handle form submission
    if (isset($_POST['update_tag'])) {
        $tag_id = intval($_POST['tag_id']);
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $description = sanitize_text_field(wp_unslash($_POST['description']));

        $wpdb->update(
            $table_name,
            ['name' => $name, 'description' => $description],
            ['tag_id' => $tag_id],
            ['%s', '%s'],
            ['%d']
        );

        echo '<div class="updated"><p>Tag updated successfully.</p></div>';
    }

    // Fetch tag for editing
    if (isset($_GET['tag_id'])) {
        $tag_id = intval($_GET['tag_id']);
        $tag = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE tag_id = %d", $tag_id));

        if ($tag) {
            echo '<div class="wrap">';
            echo '<h1>Edit Tag</h1>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="tag_id" value="' . esc_attr($tag->tag_id) . '">';
            echo '<p>Tag Name: <input type="text" name="name" value="' . esc_attr($tag->name) . '" style="width:300px;"></p>';
            echo '<p>Tag Description: <input type="text" name="description" value="' . esc_attr($tag->description) . '" style="width:600px;"></p>';
            echo '<p><input type="submit" name="update_tag" value="Save Changes" class="button button-primary"></p>';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="error"><p>Tag not found.</p></div>';
        }
    } else {
        echo '<div class="error"><p>No tag ID provided.</p></div>';
    }
}
